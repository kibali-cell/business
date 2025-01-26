<?php

use App\Http\Controllers\CRM\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // CRM Routes
    Route::prefix('crm')->group(function () {
        Route::resource('customers', CustomerController::class)->names([
            'index' => 'crm.customers.index',
            'create' => 'crm.customers.create',
            'store' => 'crm.customers.store',
            'show' => 'crm.customers.show',
            'edit' => 'crm.customers.edit',
            'update' => 'crm.customers.update',
            'destroy' => 'crm.customers.destroy',
        ]);
    });
});