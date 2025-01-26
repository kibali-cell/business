<?php

use App\Http\Controllers\CRM\CompanyController;
use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\CRM\TaskController;
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

        Route::resource('companies', CompanyController::class)->names([
            'index' => 'crm.companies.index',
            'create' => 'crm.companies.create',
            'store' => 'crm.companies.store',
            'show' => 'crm.companies.show',
            'edit' => 'crm.companies.edit',
            'update' => 'crm.companies.update',
            'destroy' => 'crm.companies.destroy',
        ]);

        Route::resource('tasks', TaskController::class)->names([
            'index' => 'crm.tasks.index',
            'create' => 'crm.tasks.create',
            'store' => 'crm.tasks.store',
            'show' => 'crm.tasks.show',
            'edit' => 'crm.tasks.edit',
            'update' => 'crm.tasks.update',
            'destroy' => 'crm.tasks.destroy',
        ]);

        Route::put('/crm/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('crm.tasks.update-status');
    });
});


