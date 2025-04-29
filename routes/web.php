<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CollectionRequestController;
use App\Http\Controllers\VehicleController;

// Rutas públicas
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/collection-request', [CollectionRequestController::class, 'store'])->name('collection.request');

    Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::post('/vehicle/{vehicle}/toggle', [VehicleController::class, 'toggleStatus'])->name('vehicle.toggle');
    Route::delete('/vehicle/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');
});
