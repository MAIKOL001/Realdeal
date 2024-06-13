<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Order\DueOrderController;
use App\Http\Controllers\Order\OrderCompleteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderPendingController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('php/', function () {
    return phpinfo();
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    // Route::resource('/users', UserController::class); //->except(['show']);
    Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/store-settings', [ProfileController::class, 'store_settings'])->name('profile.store.settings');
    Route::post('/profile/store-settings', [ProfileController::class, 'store_settings_store'])->name('profile.store.settings.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::resource('/customers', CustomerController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/units', UnitController::class);

    // Route Products
    Route::get('products/import/', [ProductImportController::class, 'create'])->name('products.import.view');
    Route::post('products/import/', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::get('products/export/', [ProductExportController::class, 'create'])->name('products.export.store');
    Route::resource('/products', ProductController::class);
    Route::get('/qrgeneration', [ProductController::class, 'generate'])->name('qrgeneration');
    Route::get('/products/{productCode}/generate', [ProductController::class, 'generate'])
    ->name('products.generate');


    // Route POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addCartItem'])->name('pos.addCartItem');
    Route::post('/pos/cart/update/{rowId}', [PosController::class, 'updateCartItem'])->name('pos.updateCartItem');
    Route::delete('/pos/cart/delete/{rowId}', [PosController::class, 'deleteCartItem'])->name('pos.deleteCartItem');

    //Route::post('/pos/invoice', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    Route::post('invoice/create/', [InvoiceController::class, 'create'])->name('invoice.create');

    // Route Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
    Route::get('/orders/complete', OrderCompleteController::class)->name('orders.complete');
    
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

    // SHOW ORDER
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');

    // DUES
    Route::get('due/orders/', [DueOrderController::class, 'index'])->name('due.index');
    Route::get('due/order/view/{order}', [DueOrderController::class, 'show'])->name('due.show');
    Route::get('due/order/edit/{order}', [DueOrderController::class, 'edit'])->name('due.edit');
    Route::put('due/order/update/{order}', [DueOrderController::class, 'update'])->name('due.update');

    // TODO: Remove from OrderController
    Route::get('/orders/details/{order_id}/download', [OrderController::class, 'downloadInvoice'])->name('order.downloadInvoice');


    // Route Purchases
    Route::get('/purchases/approved', [PurchaseController::class, 'approvedPurchases'])->name('purchases.approvedPurchases');
    Route::get('/purchases/report', [PurchaseController::class, 'purchaseReport'])->name('purchases.purchaseReport');
    Route::get('/purchases/report/export', [PurchaseController::class, 'getPurchaseReport'])->name('purchases.getPurchaseReport');
    Route::post('/purchases/report/export', [PurchaseController::class, 'exportPurchaseReport'])->name('purchases.exportPurchaseReport');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    //Route::get('/purchases/show/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

    //Route::get('/purchases/edit/{purchase}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::post('/purchases/update/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/delete/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.delete');

    //qrcodes
    // Route::get('/products/qrgeneration', [CodeController::class,'index'])->name('products.qrgeneration');


    //Route sheet
    Route::get('/sheets', [SheetController::class, 'index'])->name('fetchingsheets');
    Route::post('/sheets', [SheetController::class, 'store'])->name('sheets.store');
    Route::get('/sheets/{sheetId}', [SheetController::class, 'show'])->name('sheets.show');
    Route::get('/waybill', [SheetController::class, 'waybills'])->name('waybill');
    Route::put('/sheets/{sheetId}/update', [SheetController::class, 'update'])->name('sheets.update');
    Route::get('/sheets/{sheetId}/waybill', [SheetController::class, 'printWaybill'])->name('sheets.printWaybill');
    Route::get('/syncsheets' , [SheetController::class, 'sync'])->name('sheetssync');
    Route::post('/updateSheet', [SheetController::class, 'updateSheet'])->name('updateSheet');
    

    // Route::post('/sheets/{sheetId}/update', [SheetController::class, 'update'])->name('sheets.update');

    //Route Distributors
    // Route::get('/distributors',[DistributorController::class, 'index'])->name('distributors.index');
    // Route::get('/distributors/create',[DistributorController::class, 'create'])->name('distributors.create');
   

    //Route Dispatch
   
Route::post('/search-order', [DispatchController::class, 'searchOrder'])->name('search.order');
    Route::get('/dispatch/create', [DispatchController::class, 'create'])->name('dispatch.create');
    Route::get('/dispatch/index', [DispatchController::class, 'index'])->name('dispatch.index');
    Route::get('/riderdispatch', [DispatchController::class, 'dispatch'])->name('dispatch.riderdispatch');
    Route::get('/dispatch/{sheetId}', [DispatchController::class, 'show'])->name('dispatch.show');
    Route::post('/dispatch/upload/{sheetId}', [DispatchController::class, 'upload'])->name('dispatch.upload');
    Route::post('/dispatch/{sheetId}/update', [DispatchController::class,'update'])->name('dispatch.update');
    Route::get('/clear-search', [DispatchController::class, 'clearSearch'])->name('clear.search');
    Route::post('/assign-rider', [DispatchController::class, 'assignRider'])->name('assign.rider');
    Route::get('/ridersheetpdfs',[DispatchController::class,'sheet'])->name('ridersheetpdfs');
    Route::post('/orders/search', [DispatchController::class, 'search'])->name('orders.search');
    Route::post('/orders/generate_pdf', [DispatchController::class,'generatePdf'])->name('orders.generate_pdf');


    //Distributors
    Route::get('/distributors', [DistributorController::class, 'index'])->name('distributors.index');
    Route::get('/distributors/create', [DistributorController::class, 'create'])->name('distributors.create');
    Route::post('/distributors', [DistributorController::class, 'store'])->name('distributors.store');
    
    // Optional routes for edit and update functionality
            Route::get('/distributors/{distributor}/edit', [DistributorController::class, 'edit'])->name('distributors.edit');
            Route::put('/distributors/{distributor}/update', [DistributorController::class, 'update'])->name('distributors.update');

            Route::delete('/distributors/{distributor}', [DistributorController::class, 'destroy'])->name('distributors.destroy');

            //qrcode generation
           
                
    // Route Quotations
    // Route::post('/quotations/complete/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    // Route::delete('/quotations/delete/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.delete');
});

require __DIR__.'/auth.php';

Route::get('test/', function (){
    return view('test');
});
