<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CollectionRequestController;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return view('auth.login');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');

// Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Solicitudes de recolección
    Route::resource('collection-requests', CollectionRequestController::class)->except(['edit', 'update']);
    Route::post('/collection-requests/{id}/accept', [CollectionRequestController::class, 'accept'])->name('collection-requests.accept');
    Route::post('/collection-requests/{id}/complete', [CollectionRequestController::class, 'complete'])->name('collection-requests.complete');
    
    // Vehículos
    Route::resource('vehicles', VehicleController::class)->except(['show']);
    Route::post('/vehicles/{id}/toggle-status', [VehicleController::class, 'toggleStatus'])->name('vehicles.toggle-status');
});
