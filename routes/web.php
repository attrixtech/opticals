<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QualityControlController;
use App\Http\Controllers\RepairController;
use Illuminate\Support\Facades\Route;

// Role Switcher & Auth
Route::get('/auth/switch/{role}', [AuthController::class, 'switchRole'])->name('auth.switch');
Route::get('/auth/login-as/{id}', [AuthController::class, 'loginAs'])->name('auth.login_as');

// Role-Based Dynamic Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Optical Point of Sale (POS)
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::post('/store', [PosController::class, 'store'])->name('store');
    Route::get('/prescriptions/{customerId}', [PosController::class, 'getCustomerPrescriptions'])->name('customer_prescriptions');
});

// Prescriptions & Optical Refractions
Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
    Route::get('/', [PrescriptionController::class, 'index'])->name('index');
    Route::get('/create', [PrescriptionController::class, 'create'])->name('create');
    Route::post('/store', [PrescriptionController::class, 'store'])->name('store');
    Route::get('/{id}', [PrescriptionController::class, 'show'])->name('show');
});

// Customer CRM
Route::prefix('customers')->name('customers.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/store', [CustomerController::class, 'store'])->name('store');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
});

// Sales Orders
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    Route::post('/{id}/payment', [OrderController::class, 'addPayment'])->name('payment');
    Route::post('/{id}/deliver', [OrderController::class, 'markDelivered'])->name('deliver');
});

// Lens Processing Laboratory & Quality Control
Route::prefix('laboratory')->name('laboratory.')->group(function () {
    Route::get('/', [LaboratoryController::class, 'index'])->name('index');
    Route::get('/{id}', [LaboratoryController::class, 'show'])->name('show');
    Route::post('/{id}/stage', [LaboratoryController::class, 'updateStage'])->name('stage');
    Route::post('/{id}/qc', [QualityControlController::class, 'store'])->name('qc');
});

// Inventory, Stock & Catalog
Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
});

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [InventoryController::class, 'products'])->name('index');
    Route::post('/store', [InventoryController::class, 'storeProduct'])->name('store');
});

// GST Invoices
Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
});

// Repairs & Warranties
Route::prefix('repairs')->name('repairs.')->group(function () {
    Route::get('/', [RepairController::class, 'index'])->name('index');
    Route::post('/store', [RepairController::class, 'store'])->name('store');
    Route::post('/{id}/status', [RepairController::class, 'updateStatus'])->name('status');
});
