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

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  protected $workOrderService;

    // public function __construct(WorkOrderService $workOrderService)
    // {
    //     $this->workOrderService = $workOrderService;
    // }

    public function index(Request $request)
    {
        $workOrderQuery = WorkOrder::query();

        // Check if 'type' exists and is not empty
        $pageSize = $request->query('pageSize');
        $pageSize = $pageSize ?? 15; // Default to 15 if not provided
        if ($request->filled('type')) {
            $workOrderQuery->where('type', $request->input('type'));
        }
        $paginatedWorkOrder= $workOrderQuery->paginate($pageSize)->appends($request->query());

        // Paginate and append query parameters to pagination links
        return new WorkOrderCollection($paginatedWorkOrder);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

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
                if( $workOrderData['type'] == 'order') {
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
                'message' => 'Failed to create workOrder',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder,Request $request)
    {
        return new WorkOrderResource($workOrder->loadMissing('products'));
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(WorkOrders $workOrders)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateWorkOrdersRequest $request, WorkOrder $workOrder)
    // {
    //     $workOrder->update($request->all());
    // }

    public function update(UpdateWorkOrderRequest $workOrderRequest, UpdateWorkOrderProductRequest $productRequest,WorkOrder $workOrder )
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

                // Mettre à jour les données principales du WorkOrder
                $workOrder->update($workOrderData);

                // Si des produits sont fournis, mettre à jour la relation
                if (isset($productData['productsWorkOrder']) && $productData['productsWorkOrder'] != [] ) {
                    // Préparer les données des produits pour la table pivot
                    $productWorkOrders = collect($productData['productsWorkOrder'])->mapWithKeys(function ($item) {
                        return [
                            $item['product_id'] => [
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                            ]
                        ];
                    })->all();

                    // Synchroniser les produits avec le WorkOrder
                    $workOrder->products()->sync($productWorkOrders);
                }

                // Mettre à jour le prix total
                $workOrder->updateTotalPrice();

                return $workOrder;
                });

            return response()->json([
                'message' => 'WorkOrder updated successfully',
                'data' => $workOrder->load('products'), // Optionnel : inclure les produits mis à jour
            ], 200);
        } catch (\Exception $e) {
            // Roll back the transaction on error
            return response()->json([
                'message' => 'Failed to update workOrder',
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

                // Log the deletion
                // Log::create([
                //     'user_id' => auth()->id(),
                //     'action' => 'delete_work_order',
                //     'details' => json_encode([
                //         'workorder_id' => $workOrder->id,
                //         'workorder_number' => $workOrder->workorder_number,
                //     ]),
                // ]);
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

    public function downloadPdf($id)
{
    try {
        
        $workOrder = Workorder::with(['products', 'vehicle.brand', 'customer'])->findOrFail($id);

         // Récupérer les informations de la société
         $company = Setting::first(); // Supposons que les infos de la société sont stockées dans une table "settings"

      

        // Préparer les données pour la vue
        $data = [
            'workOrder' => $workOrder, // Utiliser le modèle brut, pas une ressource
            'company' => $company,
            'totalTTC' => $workOrder->total * 1.20, // TVA 20%
        ];

        // Générer le PDF à partir de la vue
        $pdf = Pdf::loadView('pdf.quote', $data);
       

        // Télécharger le PDF
         return $pdf->stream('devis-' . $workOrder->workorderNumber . '.pdf');
    } catch (\Exception $e) {
        // Log l’erreur pour le débogage
        \Log::error('Erreur lors de la génération du PDF : ' . $e->getMessage());

        // Retourner une réponse d’erreur (facultatif, selon ton besoin)
        return response()->json([
            'message' => 'Échec de la génération du PDF',
            'error' => $e->getMessage(),
        ], 500);
    }
}

private function generateInvoiceNumber()
{
    $latestInvoice = Invoice::latest()->first();
    $number = $latestInvoice ? (int) str_replace('INV-', '', $latestInvoice->invoice_number) + 1 : 1;
    return 'INV-' . str_pad($number, 3, '0', STR_PAD_LEFT);
}
}
