<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('dashboard/index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // User
    Route::get('/users/load', [UserController::class, 'loadDataTable'])->name('users.load');
    Route::put('users/{id}/activate', [UserController::class, 'activateUser'])->name('users.activate');
    Route::put('users/{id}/deactivate', [UserController::class, 'deactivateUser'])->name('users.deactivate');
    Route::resource('/users', UserController::class)->except(['show', 'destroy']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
