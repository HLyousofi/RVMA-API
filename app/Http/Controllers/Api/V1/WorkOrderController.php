<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\WorkOrder;
use App\Models\Setting;
use App\Http\Requests\V1\StoreWorkOrderRequest;
use App\Http\Requests\V1\UpdateWorkOrderRequest;
use App\Http\Requests\V1\StoreWorkOrderProductRequest;
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
        $query = WorkOrder::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return new WorkOrderCollection($query->paginate()->appends($request->query()));
        // $query = WorkOrder::query();

        // if ($request->has('type')) {
        //     $query->where('type', $request->type);
        // }
       
         
        // return new WorkOrderCollection($workOrders->paginate()->appends($request->query()));
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

    public function update(UpdateWorkOrderRequest $workOrderRequest, StoreWorkOrderProductRequest $productRequest,WorkOrder $workOrder )
    {     
        try {
            // Start a database transaction
            $workOrder = DB::transaction(function () use ($workOrderRequest, $productRequest, $workOrder) {

                // Get validated data
                $workOrderData = $workOrderRequest->validated();
                $productData = $productRequest->validated();

                // Mettre à jour les données principales du WorkOrder
                $workOrder->update($workOrderData);

                // Si des produits sont fournis, mettre à jour la relation
                if (isset($productData['productsWorkOrder'])) {
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
    public function destroy(WorkOrders $workOrder)
    {
        //
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
}
