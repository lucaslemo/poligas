<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ACL\PermissionController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VendorController;
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

Route::get('/', function () {
    return view('dashboard/index');
})->middleware(['auth'])->name('dashboard');

// Grupo de rotas para administradores e gerentes
Route::group(['middleware' => ['auth', 'role:Administrador|Gerente']], function () {
    // Vendas
    Route::get('/prices/load', [PriceController::class, 'loadDataTable'])->name('prices.load');
    Route::resource('/prices', PriceController::class);

    // Sales
    Route::get('/sales/load', [SaleController::class, 'loadDataTable'])->name('sales.load');
    Route::get('/sales/{filter}/loadCard', [SaleController::class, 'loadCard'])->name('sales.loadCard');
    Route::get('/sales/{filter}/{chartType}/charts', [SaleController::class, 'loadChart'])->name('sales.loadChart');
    Route::put('/sales/{id}/assignStocks', [SaleController::class, 'assignStocks'])->name('sales.assignStocks');
    Route::resource('/sales', SaleController::class);

    // Stocks
    Route::get('/stocks/load', [StockController::class, 'loadDataTable'])->name('stocks.load');
    Route::get('/stocks/productStocks/{product}', [StockController::class, 'infoProductStocks'])->name('stocks.productStocks');
    Route::put('/stocks/{id}/unassignSale', [StockController::class, 'unassignSale'])->name('stocks.unassignSale');
    Route::resource('/stocks', StockController::class)->except(['edit', 'update']);

    // Vendors
    Route::get('/vendors/load', [VendorController::class, 'loadDataTable'])->name('vendors.load');
    Route::get('/vendors/getVendors', [VendorController::class, 'getVendors'])->name('vendors.getVendors');
    Route::get('/vendors/{id}/getVendor', [VendorController::class, 'getVendor'])->name('vendors.getVendor');
    Route::resource('/vendors', VendorController::class)->except(['show', 'destroy']);

    // Brands
    Route::get('/brands/load', [BrandController::class, 'loadDataTable'])->name('brands.load');
    Route::get('/brands/getBrands', [BrandController::class, 'getBrands'])->name('brands.getBrands');
    Route::get('/brands/{id}/getBrand', [BrandController::class, 'getBrand'])->name('brands.getBrand');
    Route::resource('/brands', BrandController::class)->except(['show', 'destroy']);

    // Products
    Route::get('/products/load', [ProductController::class, 'loadDataTable'])->name('products.load');
    Route::get('/products/getProducts', [ProductController::class, 'getProducts'])->name('products.getProducts');
    Route::get('/products/{id}/getProduct', [ProductController::class, 'getProduct'])->name('products.getProduct');
    Route::resource('/products', ProductController::class)->except(['show', 'destroy']);

    // Address
    Route::get('/addresses/load', [AddressController::class, 'loadDataTable'])->name('addresses.load');
    Route::put('/addresses/{id}/primary', [AddressController::class, 'primary'])->name('addresses.primary');
    Route::resource('/addresses', AddressController::class)->only(['store', 'edit', 'update', 'destroy']);

    // Customer
    Route::get('/customers/load/{type?}', [CustomerController::class, 'loadDataTable'])->name('customers.load');
    Route::get('/customers/getCustomers', [CustomerController::class, 'getCustomers'])->name('customers.getCustomers');
    Route::get('/customers/{id}/getCustomer', [CustomerController::class, 'getCustomer'])->name('customers.getCustomer');
    Route::resource('/customers', CustomerController::class)->except(['show', 'destroy']);

    // User
    Route::get('/users/load/{role?}', [UserController::class, 'loadDataTable'])->name('users.load');
    Route::get('/users/deliveryMen', [UserController::class, 'deliveryMenIndex'])->name('users.deliveryMen');
});

// Grupo de rotas para administradores
Route::group(['middleware' => ['auth', 'role:Administrador']], function () {
    // Permission
    Route::get('/permissions/load', [PermissionController::class, 'loadDataTable'])->name('permissions.load');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    // User
    Route::get('/users/getUsers/{role?}', [UserController::class, 'getUsers'])->name('users.getUsers');
    Route::get('/users/{id}/getUser', [UserController::class, 'getUser'])->name('users.getUser');
    Route::put('/users/{id}/activate', [UserController::class, 'activateUser'])->name('users.activate');
    Route::put('/users/{id}/deactivate', [UserController::class, 'deactivateUser'])->name('users.deactivate');
    Route::put('/users/{id}/assignDeliveryman', [UserController::class, 'assignDeliveryman'])->name('users.assignDeliveryman');
    Route::put('/users/{id}/unassignDeliveryman', [UserController::class, 'unassignDeliveryman'])->name('users.unassignDeliveryman');
    Route::resource('/users', UserController::class)->except(['show', 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__ . '/auth.php';
