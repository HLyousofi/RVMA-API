<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Models\Setting;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Controllers\Controller;
use App\Filters\V1\InvoiceFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\V1\UpdateWorkOrderProductRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices, filtered by query parameters.
     * Results are cached in Redis for 10 minutes.
     *
     * @param Request $request
     * @return InvoiceCollection
     */
    public function index(Request $request)
    {
        // Initialize the InvoiceFilter to transform query parameters
        $filter = new InvoiceFilter();
        $queryItems = $filter->transform($request);

        // Get the pageSize query parameter
        $pageSize = $request->query('pageSize');

        // Generate a unique cache key based on query parameters
        $cacheKey = 'invoices:' . md5(json_encode([
            'filters' => ksort($queryItems),
            'page' => $request->query('page', 1),
            'pageSize' => $pageSize,
        ], JSON_FORCE_OBJECT));

        // Cache TTL: 10 minutes
        $cacheTTL = now()->addMinutes(60);

        // Build the query
        $invoiceQuery = Invoice::query();
       

        // Default to 15 items per page if pageSize is not provided
        $pageSize = $pageSize ?? 15;

        // Cache and retrieve paginated invoices
        $paginatedInvoices = Cache::tags(['invoices'])->remember($cacheKey, $cacheTTL, function () use ($invoiceQuery, $pageSize, $request, $cacheKey) {
            Log::info("Caching paginated invoices with key: {$cacheKey}");
            return $invoiceQuery->orderBy('created_at', 'desc')->paginate($pageSize)->appends($request->query());
        });

        return new InvoiceCollection($paginatedInvoices);
    }

    /**
     * Store a newly created invoice in storage and clear relevant cache.
     */
    public function store(StoreInvoiceRequest $request)
    {
        try {
            // Start a database transaction
            $invoice = DB::transaction(function () use ($request) {
                // Create the invoice
                $invoice = Invoice::create($request->validated());
                return $invoice;
            });


            return new InvoiceResource($invoice);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice, Request $request)
    {
        return new InvoiceResource($invoice->loadMissing('products'));
    }

    /**
     * Update the specified invoice in storage and clear relevant cache.
     */
    public function update(UpdateInvoiceRequest $invoiceRequest, UpdateWorkOrderProductRequest $productRequest, Invoice $invoice)
    {
        try {
            // Start a database transaction
            $invoice = DB::transaction(function () use ($invoiceRequest, $productRequest, $invoice) {
                // Get validated data
                $invoiceData = $invoiceRequest->validated();
                $productData = $productRequest->validated();

                if (isset($invoiceData['status'])) {
                    $newStatus = $invoiceData['status'];
    
                    // Set sent_at if status changes to 'sent' and not already set
                    if ($newStatus === 'issued' && !$invoice->sent_at) {
                        $invoiceData['billed_date'] = now();
                    }
    
                    // Set paid_at if status changes to 'paid' and not already set
                    if ($newStatus === 'paid' && !$invoice->paid_at) {
                        $invoiceData['paid_date'] = now();
                    }
                }
              

                // Handle status change to 'draft'
                if (isset($invoiceData['status']) && $invoiceData['status'] === 'draft') {
                    // Sync existing products
                    $invoiceProducts = $invoice->products->mapWithKeys(function ($product) {
                        return [
                            $product->id => [
                                'quantity' => $product->pivot->quantity,
                                'unit_price' => $product->pivot->unit_price,
                            ]
                        ];
                    })->all();
                    $invoice->products()->sync($invoiceProducts);
                }

                // Update the invoice data
                $invoice->update($invoiceData);

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

                    // Sync products with the invoice
                    $invoice->products()->sync($productWorkOrders);
                }

                // Update the total price
                $invoice->updateTotalPrice();

                return $invoice;
            });

            // // Clear cache for invoices
            // $this->clearInvoiceCache();
          

            return response()->json([
                'message' => 'Invoice updated successfully',
                'data' => $invoice->load('products'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified invoice from storage and clear relevant cache.
     */
    public function destroy(Invoice $invoice)
    {
        try {
            // Delete within a transaction
            DB::transaction(function () use ($invoice) {
                $invoice->delete();
            });
            // $this->clearInvoiceCache();

            return response()->json([
                'message' => 'Invoice deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate and stream a PDF for the specified invoice.
     */
    public function downloadPdf($id)
    {
        try {
            $invoice = Invoice::with(['products', 'vehicle.brand', 'customer'])->findOrFail($id);

            // Retrieve company information
            $company = Setting::first();

            // Prepare data for the PDF view
            $data = [
                'invoice' => $invoice,
                'company' => $company,
                'totalTTC' => $invoice->amount * 1.20, // Apply 20% VAT
            ];

            // Generate PDF from the view
            $pdf = Pdf::loadView('pdf.invoice', $data);

            // Stream the PDF
            return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error generating PDF: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}