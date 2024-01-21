<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ACL\PermissionController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
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
    // Brands
    Route::get('/brands/load', [BrandController::class, 'loadDataTable'])->name('brands.load');
    Route::resource('/brands', BrandController::class);

    // Products
    Route::get('/products/load', [ProductController::class, 'loadDataTable'])->name('products.load');
    Route::resource('/products', ProductController::class);

    // Address
    Route::get('/addresses/load', [AddressController::class, 'loadDataTable'])->name('addresses.load');
    Route::put('/addresses/{id}/primary', [AddressController::class, 'primary'])->name('addresses.primary');
    Route::resource('/addresses', AddressController::class)->only(['store', 'edit', 'update', 'destroy']);

    // Customer
    Route::get('/customers/load/{type?}', [CustomerController::class, 'loadDataTable'])->name('customers.load');
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

require __DIR__.'/auth.php';
