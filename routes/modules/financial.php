<?php

use App\Http\Controllers\Financial\AccountController;
use App\Http\Controllers\Financial\TransactionController;
use App\Http\Controllers\Financial\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('financial')->group(function () {
    // Account routes
    Route::resource('accounts', AccountController::class);
    
    // Transaction routes
    Route::resource('transactions', TransactionController::class);
    Route::post('transactions/approve/{transaction}', [TransactionController::class, 'approve'])
         ->name('transactions.approve');
    
    // Invoice routes
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])
         ->name('invoices.send');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])
         ->name('invoices.download');
});