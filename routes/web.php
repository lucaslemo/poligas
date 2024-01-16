<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ACL\PermissionController;
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

// Grupo de rotas para administradores
Route::group(['middleware' => ['auth', 'role:Administrador']], function () {
    // Permission
    Route::get('/permissions/load', [PermissionController::class, 'loadDataTable'])->name('permissions.load');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    // User
    Route::get('/users/load/{role?}', [UserController::class, 'loadDataTable'])->name('users.load');
    Route::get('users/getUsers/{role?}', [UserController::class, 'getUsers'])->name('users.getUsers');
    Route::put('users/{id}/activate', [UserController::class, 'activateUser'])->name('users.activate');
    Route::put('users/{id}/deactivate', [UserController::class, 'deactivateUser'])->name('users.deactivate');
    Route::put('users/{id}/assignDeliveryman', [UserController::class, 'assignDeliveryman'])->name('users.assignDeliveryman');
    Route::put('users/{id}/unassignDeliveryman', [UserController::class, 'unassignDeliveryman'])->name('users.unassignDeliveryman');
    Route::resource('/users', UserController::class)->except(['show', 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
