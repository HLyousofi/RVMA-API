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


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoiceQuery = Invoice::query();

        // Check if 'type' exists and is not empty
        $pageSize = $request->query('pageSize');
        $pageSize = $pageSize ?? 15; // Default to 15 if not provided
        $paginatedInvoices= $invoiceQuery->paginate($pageSize)->appends($request->query());

        // Paginate and append query parameters to pagination links
        return new InvoiceCollection($paginatedInvoices);

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
    public function store(StoreInvoiceRequest $request)
    {
        return new InvoiceResource(Invoice::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice, Request $request)
    {
        
        return new InvoiceResource($invoice->loadMissing('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Invoice $invoice)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $invoiceRequest,UpdateWorkOrderProductRequest $productRequest,Invoice $invoice)
    {
        // $invoice->update($request->all());
        try {
            // Start a database transaction
            $invoice = DB::transaction(function () use ($invoiceRequest, $productRequest, $invoice) {

                // Get validated data
                $invoiceData = $invoiceRequest->validated();
                $productData = $productRequest->validated();
                \Log::info('[' . basename(__FILE__) . ':' . __LINE__ . '] Copied Object :', [
                    'data' => $invoiceData
                ]);
                \Log::info('[' . basename(__FILE__) . ':' . __LINE__ . '] Copied Object :', [
                    'data' => $productData
                ]);

          
            
                 // Handle status change to 'invoicing'
                 if (isset($invoiceData['status']) && $invoiceData['status'] === 'draft') {
                    // Copy products from order_product to invoice_product
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

                // Mettre à jour les données principales du WorkOrder
                $invoice->update($invoiceData);

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
                    $invoice->products()->sync($productWorkOrders);
                }

                // Mettre à jour le prix total
                $invoice->updateTotalPrice();

                return $invoice;
                });

            return response()->json([
                'message' => 'WorkOrder updated successfully',
                'data' => $invoice->load('products'), // Optionnel : inclure les produits mis à jour
            ], 200);
        } catch (\Exception $e) {
            // Roll back the transaction on error
            return response()->json([
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
    }

    public function downloadPdf($id)
    {
        try {
            
            $invoice = Invoice::with(['products', 'vehicle.brand', 'customer'])->findOrFail($id);
    
             // Récupérer les informations de la société
             $company = Setting::first(); // Supposons que les infos de la société sont stockées dans une table "settings"
    
          
    
            // Préparer les données pour la vue
            $data = [
                'invoice' => $invoice, // Utiliser le modèle brut, pas une ressource
                'company' => $company,
                'totalTTC' => $invoice->amount * 1.20, // TVA 20%
            ];
    
            // Générer le PDF à partir de la vue
            $pdf = Pdf::loadView('pdf.invoice', $data);
           
    
            // Télécharger le PDF
             return $pdf->stream('devis-' . $invoice->workorderNumber . '.pdf');
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
    

}
