<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BakeryController;
use App\Http\Controllers\ObjectDetectionController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProductBuyController;
use App\Http\Controllers\EditOrderController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//Route::get('/', function () {
//    return view ('welcome');
//});


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('ownerHome', [DashboardController::class, 'ownerHome'])->name('ownerHome')->middleware('is_admin');
Route::get('Bakery', [BakeryController::class, 'index']);
Route::get('Product', [ProductController::class, 'index'])->middleware('is_admin');
Route::get('lowInStock', [ProductController::class, 'lowInStock'])->middleware('is_admin');
Route::get('AddProduct', [ProductController::class, 'create'])->middleware('is_admin');
Route::post('EditProduct/{Bakery_ID}', [ProductController::class, 'edit'])->name('editP')->middleware('is_admin');
Route::post('AddProduct', [ProductController::class, 'add'])->middleware('is_admin');
Route::put('UpdateProduct/{Bakery_ID}', [ProductController::class, 'update'])->name('updateP')->middleware('is_admin');
Route::post('DeleteProduct', [ProductController::class, 'delete'])->name('deleteP')->middleware('is_admin');
Route::get('File', [FileController::class, 'index'])->middleware('is_admin');
Route::get('UploadFile', [FileController::class, 'createFile'])->middleware('is_admin');
Route::post('UploadFile', [FileController::class, 'uploadFile'])->middleware('is_admin');
Route::post('EditFile/{File_ID}', [FileController::class, 'edit'])->name('editF')->middleware('is_admin');
Route::put('UpdateFile/{File_ID}', [FileController::class, 'update'])->name('updateF')->middleware('is_admin');
Route::get('UploadAIB', [FileController::class, 'create'])->middleware('is_admin');
Route::post('UploadAIB', [FileController::class, 'uploadAIB'])->middleware('is_admin');
Route::post('DeleteFile', [FileController::class, 'delete'])->name('deleteF')->middleware('is_admin');



Route::get('ProductBuy', [ProductBuyController::class, 'index']);
Route::get('productbuycreate', [ProductBuyController::class, 'create']);
Route::post('productbuyedit/{Product_ID}', [ProductBuyController::class, 'edit'])->name('editPb');
Route::post('productbuycreate', [ProductBuyController::class, 'add']);
Route::put('UpdateProductBuy/{Product_ID}', [ProductBuyController::class, 'update'])->name('updatePb');
Route::post('DeleteProductBuy', [ProductBuyController::class, 'delete'])->name('deletePb');

Route::get('Detect', [ObjectDetectionController::class, 'detect'])->name('detectP');
Route::get('History', [HistoryController::class, 'index']);

Route::match(['get', 'post'], 'editorder', [EditOrderController::class, 'index'])->name('editorder');
Route::post('editorderincrease', [EditOrderController::class, 'increaseQuantity'])->name('increaseQuantity');
Route::post('/EditBakery/generateQR', [EditOrderController::class, 'genQR'])->name('genQR');
Route::post('/cashPay', [EditOrderController::class, 'CashPay'])->name('CashPay');
Route::post('/clearOrder', [EditOrderController::class, 'clearOrder'])->name('clearOrder');
Route::post('PromptPay', [EditOrderController::class, 'PromptPay'])->name('PromptPay');
Route::post('/invoice/{order_id}', [EditOrderController::class, 'viewInvoice']);
Route::post('/print-invoice/{order_id}/generate', [EditOrderController::class, 'printInvoice'])->name('printInvoice');

Route::get('SellManage', [HistoryController::class, 'SellManage'])->name('sellM')->middleware('is_admin');
Route::get('/Order-Detail/{order_id}', [HistoryController::class, 'OrderDetail'])->name('OrderD')->middleware('is_admin');
Route::get('CreateOrder', [HistoryController::class, 'create'])->middleware('is_admin');
Route::post('/insertOrder', [HistoryController::class, 'insert'])->name('insertOrder')->middleware('is_admin');
Route::post('editOrderDetail/{order_id}', [HistoryController::class, 'edit'])->name('editOrderDetail')->middleware('is_admin');
Route::put('UpdateOrderDetail/{order_id}', [HistoryController::class, 'update'])->name('updateOD')->middleware('is_admin');
Route::post('DeleteOrder', [HistoryController::class, 'delete'])->name('deleteOR')->middleware('is_admin');
Route::get('/history/filter', [HistoryController::class, 'filter'])->name('history.filter');
Route::get('/sellM/filter', [HistoryController::class, 'filterSellM'])->name('sellM.filter')->middleware('is_admin');

Route::get('/search-bakery', [ProductController::class, 'searchProducts']);
Route::get('Ai', [AIController::class, 'index'])->name('Ai');
Route::post('/update-status/{id}', [AIController::class, 'updateStatus'])->name('update.status');
Route::get('/search-bakeryai', [AIController::class, 'search'])->name('search.bakeryai');

Route::get('User', [UserController::class, 'index'])->name('User');
Route::get('createuser', [UserController::class, 'create']);
Route::post('/insertUser', [UserController::class, 'insert'])->name('insertUser');
Route::get('edituser/{id}', [UserController::class, 'edit'])->name('editUs');
Route::post('createuser', [UserController::class, 'add']);
Route::put('userupdate/{id}', [UserController::class, 'update'])->name('updateUs');
Route::post('userdelete', [UserController::class, 'delete'])->name('deleteUs');

Route::get('/searchBakery', [BakeryController::class, 'searchBakery'])->name('searchBakery');
Route::get('/search-Bakery', [ProductController::class, 'searchBakery'])->name('search-bakery');
Route::get('/searchProductBuy', [ProductBuyController::class, 'searchProductBuy'])->name('searchProductBuy');

Route::get('/api/getMonthlySales', [DashboardController::class, 'getMonthlySales']);
Route::get('/api/getMonthlyOrders', [DashboardController::class, 'getMonthlyOrders']);
Route::get('/api/getMonthlySaleQuan', [DashboardController::class, 'getMonthlySaleQuan']);
Route::get('/dbSale', [DashboardController::class, 'dbSale']);
Route::get('/dbBuy', [DashboardController::class, 'dbBuy']);
Route::get('/dbProduct', [DashboardController::class, 'dbPro']);
Route::get('/MonthNow', [DashboardController::class, 'MonthNow']);

Route::get('Bakery-Detail/{Bakery_ID}', [ProductController::class, 'detail'])->name('detailB');
Route::post('DeleteStock', [ProductController::class, 'deleteStock'])->name('deleteS');
Route::post('AddStock/{Bakery_ID}', [ProductController::class, 'addStock'])->name('addS');
Route::post('/updateStock/{id}', [ProductController::class, 'updateStock'])->name('updateStock');
Route::get('Bakery_Detail/{Bakery_ID}', [BakeryController::class, 'detail'])->name('DetailB');

Route::get('/qrcode-view', function (Request $request) {
    $qrSrc = $request->input('qrSrc');
    $amount = $request->input('amount');
    return view('qrcode-view', compact('qrSrc', 'amount'));
})->name('qrcode.view');