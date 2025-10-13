<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\WorkOrderController;
use App\Http\Controllers\Api\V1\InvoiceController;
use Illuminate\Support\Facades\Log;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refreshToken');

});



Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'auth:sanctum'], function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('supplier', SupplierController::class);
    Route::apiResource('transaction', TransactionController::class);
    Route::apiResource('fueltypes', FuelTypeController::class);
    Route::apiResource('carBrands', CarBrandController::class);
    Route::apiResource('workOrder', WorkOrderController::class);
    Route::apiResource('stocks', StockController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('settings', SettingController::class);
    Route::post('/workorders/{id}/pdf', [WorkOrderController::class, 'downloadPdf'])->name('downloadWorkOrderPdf');
    Route::post('/invoices/{id}/pdf', [InvoiceController::class, 'downloadPdf'])->name('downloadInvoicePdf');
  
});
