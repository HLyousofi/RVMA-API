<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\WorkOrder;
use App\Models\Setting;
use App\Models\Invoice;
use App\Http\Requests\V1\StoreWorkOrderRequest;
use App\Http\Requests\V1\UpdateWorkOrderRequest;
use App\Http\Requests\V1\StoreWorkOrderProductRequest;
use App\Http\Requests\V1\UpdateWorkOrderProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\WorkOrderCollection;
use App\Http\Resources\V1\WorkOrderResource;
use App\Filters\V1\WorkOrderFilter;
use Illuminate\Http\Request;
use App\services\WorkOrderService;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of work orders, filtered by type and other query parameters.
     * Results are cached in Redis for 10 minutes, with separate cache keys for quotes and orders.
     *
     * @param Request $request
     * @return WorkOrderCollection
     */
    public function index(Request $request)
    {
        // Initialize the WorkOrderFilter to transform query parameters
        $filter = new WorkOrderFilter();

        // Get the pageSize and type query parameters
        $pageSize = $request->query('pageSize');
        $type = $request->input('type'); // e.g., 'quote' or 'order'

        // Set cache key prefix based on type (quotes or orders)
        $cacheKeyPrefix = $type === 'quote' ? 'quotes' : 'orders';

        // Generate a unique cache key based on query parameters
        $cacheKey = $cacheKeyPrefix . md5(json_encode([
            'page' => $request->query('page', 1),
            'pageSize' => $pageSize,
        ]));

        // Cache TTL: 10 minutes
        $cacheTTL = now()->addMinutes(60);

        // Build the query
        $workOrderQuery = WorkOrder::query();
        if ($type) {
            $workOrderQuery->where('type', $type);
        }
     

        // Default to 15 items per page if pageSize is not provided
        $pageSize = $pageSize ?? 15;

        // Cache and retrieve paginated work orders
        $paginatedWorkOrders = Cache::tags([$cacheKeyPrefix])->remember($cacheKey, $cacheTTL, function () use ($workOrderQuery, $pageSize, $request, $cacheKey) {
            return $workOrderQuery->orderBy('created_at', 'desc')->paginate($pageSize)->appends($request->query());
        });

        return new WorkOrderCollection($paginatedWorkOrders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOrderRequest $workOrderRequest, StoreWorkOrderProductRequest $productRequest)
    {     
        try {
            // Start a database transaction
            $workOrder = DB::transaction(function () use ($workOrderRequest, $productRequest) {
                // Get validated data
                $workOrderData = $workOrderRequest->validated();
                $productData = $productRequest->validated();
                if ($workOrderData['type'] == 'order') {
                    $workOrderData['order_date'] = now()->toDateTimeString();
                }
              
                // Create the workOrder
                $workOrder = WorkOrder::create($workOrderData);

                // Prepare product data for the pivot table
                $productWorkOrders = collect($productData['productsWorkOrder'])->mapWithKeys(function ($item) {
                    return [
                        $item['product_id'] => [
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                        ]
                    ];
                })->all();
               
                // Attach products to the workOrder
                $workOrder->products()->sync($productWorkOrders);

                $workOrder->updateTotalPrice();
                return $workOrder;
            });


            return response()->json([
                'message' => 'WorkOrder created successfully',
            ], 201);
        } catch (\Exception $e) {
            // Roll back the transaction on error
            return response()->json([
                'message' => 'Failed to create WorkOrder',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder, Request $request)
    {
        return new WorkOrderResource($workOrder->loadMissing('products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkOrderRequest $workOrderRequest, UpdateWorkOrderProductRequest $productRequest, WorkOrder $workOrder)
    {     
        try {
            // Start a database transaction
            $workOrder = DB::transaction(function () use ($workOrderRequest, $productRequest, $workOrder) {
                // Get validated data
                $workOrderData = $workOrderRequest->validated();
                $productData = $productRequest->validated();
                // Check if status is 'converted' using array syntax
                if ($workOrderData['status'] === 'converted') {
                    $workOrderData['status'] = 'pending';
                    $workOrderData['type'] = 'order';
                    $workOrderData['quote_number'] = $workOrder['workorder_number'];
                    $workOrderData['order_date'] = now();
                }
                // Handle status change to 'invoicing'
                if (isset($workOrderData['status']) && $workOrderData['status'] === 'to_invoice') {
                    // Ensure work order is in a valid state for invoicing
                    if ($workOrder->status !== 'completed') {
                        throw new \Exception('Work order must be completed to create an invoice');
                    }

                    // Create invoice
                    $invoice = Invoice::create([
                        'workorder_id' => $workOrder->id,
                        'invoice_number' => $this->generateInvoiceNumber(),
                        'customer_id' => $workOrder->customer_id,
                        'vehicle_id' => $workOrder->vehicle_id,
                        'amount' => $workOrder->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->unit_price),
                        'discount' => 0, // Default, can be updated later
                        'status' => 'draft',
                        'billed_date' => null,
                        'paid_date' => null,
                    ]);

                    // Copy products from order_product to invoice_product
                    $invoiceProducts = $workOrder->products->mapWithKeys(function ($product) {
                        return [
                            $product->id => [
                                'quantity' => $product->pivot->quantity,
                                'unit_price' => $product->pivot->unit_price,
                            ]
                        ];
                    })->all();

                    $invoice->products()->sync($invoiceProducts);

                    // Update work order status to 'invoiced'
                    $workOrderData['status'] = 'to_invoice';
                }

                // Update the WorkOrder data
                

                // If products are provided, update the relationship
                if (isset($productData['productsWorkOrder']) && !empty($productData['productsWorkOrder'])) {
                    // Prepare product data for the pivot table
                    $productWorkOrders = collect($productData['productsWorkOrder'])->mapWithKeys(function ($item) {
                        return [
                            $item['product_id'] => [
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                            ]
                        ];
                    })->all();

                    // Sync products with the WorkOrder
                    $workOrder->products()->sync($productWorkOrders);
                }
                
                $workOrder->update($workOrderData);
                $workOrder->updateTotalPrice();

                return $workOrder;
            });

            return response()->json([
                'message' => 'WorkOrder updated successfully',
                'data' => $workOrder->load('products'),
            ], 200);
        } catch (\Exception $e) {
            // Roll back the transaction on error
            return response()->json([
                'message' => 'Failed to update WorkOrder',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        try {
            // Check status constraint
            if ($workOrder->status !== 'draft') {
                return response()->json([
                    'message' => 'Cannot delete work order. Only draft work orders can be deleted.',
                ], 403);
            }

            // Check for related invoices
            if ($workOrder->invoice()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete work order. It has associated invoices.',
                ], 403);
            }

            // Delete within a transaction
            DB::transaction(function () use ($workOrder) {
                // Delete the work order (cascades to order_product due to onDelete('cascade'))
                $workOrder->delete();
            });

            return response()->json([
                'message' => 'Work order deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete work order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate and stream a PDF for the specified work order.
     */
    public function downloadPdf($id)
    {
        
        try {
            $workOrder = WorkOrder::with(['products', 'vehicle.brand', 'customer'])->findOrFail($id);

            // Retrieve company information
            $company = Setting::first();

            // Prepare data for the PDF view
            $data = [
                'workOrder' => $workOrder,
                'company' => $company,
                'totalTTC' => $workOrder->total * 1.20, // Apply 20% VAT
            ];

            // Generate PDF from the view
            $pdf = Pdf::loadView('pdf.quote', $data);

            // Stream the PDF
            return $pdf->stream('devis-' . $workOrder->workorderNumber . '.pdf');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error generating PDF: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate a unique invoice number.
     */
    

     private function generateInvoiceNumber()
    {
        $prefix = config('app.invoice_prefix', 'INV');
        $startNumber = config('app.invoice_start_number', 1);

        $latestInvoice = Invoice::latest()->first();
        $number = $latestInvoice 
            ? (int) str_replace($prefix . '-', '', $latestInvoice->invoice_number) + 1 
            : $startNumber;

        return $prefix.str_pad(max($startNumber, $number), 3, '0', STR_PAD_LEFT);
    }

}