<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\CRM\CompanyController;
use App\Http\Controllers\CRM\TaskController;
use App\Http\Controllers\CRM\TaskTemplateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\Finance\AccountController;
use App\Http\Controllers\Finance\TransactionController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Finance\ExpenseController;
use App\Http\Controllers\Finance\ReportController;
use App\Http\Controllers\Finance\BudgetController;
use App\Http\Controllers\Finance\BankTransactionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;

use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;


// Redirect unauthenticated users to the login page before showing the home page.
Route::get('/', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    return 'Admin Dashboard';
})->middleware(['auth', 'role:admin']);

// Document and Folder Routes
Route::middleware('auth')->group(function () {
    // Document routes
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::post('/{document}/access', [DocumentController::class, 'manageAccess'])
             ->name('documents.manage-access');
    });

    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');

    // Folder routes
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // Account Routes
    Route::prefix('finance/accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('finance.accounts.index');
        Route::get('/create', [AccountController::class, 'create'])->name('finance.accounts.create');
        Route::post('/', [AccountController::class, 'store'])->name('finance.accounts.store');
        Route::get('/{account}', [AccountController::class, 'show'])->name('finance.accounts.show');
        Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('finance.accounts.edit');
        Route::put('/{account}', [AccountController::class, 'update'])->name('finance.accounts.update');
        Route::delete('/{account}', [AccountController::class, 'destroy'])->name('finance.accounts.destroy');
    });

    // Transaction Routes
    Route::prefix('finance/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('finance.transactions.index');
        Route::get('/create', [TransactionController::class, 'create'])->name('finance.transactions.create');
        Route::post('/', [TransactionController::class, 'store'])->name('finance.transactions.store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('finance.transactions.show');
        Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('finance.transactions.edit');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('finance.transactions.update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('finance.transactions.destroy');
    });

    // Invoice Routes (updated)
    Route::prefix('finance/invoices')->name('finance.invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');

        Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('download');
    });


    Route::prefix('finance/expenses')->name('finance.expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
        Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
        
        // New route for status update (for approval/rejection)
        Route::put('/{expense}/status', [ExpenseController::class, 'updateStatus'])->name('status');
    });

    Route::prefix('finance/reports')->name('finance.reports.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/export', function() {
            return Excel::download(new FinancialReportExport, 'financial_report.xlsx');
        })->name('export');
    });
    
    Route::prefix('finance/budgets')->name('finance.budgets.')->group(function () {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::get('/create', [BudgetController::class, 'create'])->name('create');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::get('/{budget}', [BudgetController::class, 'show'])->name('show');
        Route::get('/{budget}/edit', [BudgetController::class, 'edit'])->name('edit');
        Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('finance/bank-transactions')->name('finance.bank-transactions.')->group(function () {
        Route::get('/', [BankTransactionController::class, 'index'])->name('index');
        Route::post('/sync', [BankTransactionController::class, 'sync'])->name('sync');
    });

    // Inventory Routes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        // Place the reports route first (since it's a static route)
        Route::get('/valuation', [InventoryController::class, 'valuation'])->name('valuation');
        Route::get('/reports', [InventoryController::class, 'reports'])->name('reports');

        // Then define the other routes
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        
        // Dynamic routes with parameters should come last
        Route::get('/{product}', [InventoryController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{product}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/{product}', [InventoryController::class, 'destroy'])->name('destroy');

        // Record a transaction (stock in/out)
        Route::post('/{product}/transactions', [InventoryController::class, 'recordTransaction'])->name('recordTransaction');

        Route::post('/{product}/transfer', [InventoryController::class, 'transferStock'])->name('transferStock');
    });


    Route::prefix('purchase-orders')->name('purchase_orders.')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{supplier}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('auth')->prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseController::class, 'create'])->name('create');
        Route::post('/', [WarehouseController::class, 'store'])->name('store');
        Route::get('/{warehouse}', [WarehouseController::class, 'show'])->name('show');
        Route::get('/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('edit');
        Route::put('/{warehouse}', [WarehouseController::class, 'update'])->name('update');
        Route::delete('/{warehouse}', [WarehouseController::class, 'destroy'])->name('destroy');
    });

});

// Logout Route
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/'); // Redirect to home or login page after logout
})->name('logout');

// Authentication Routes
require __DIR__.'/auth.php';

// CRM Module Routes (if needed)
require __DIR__.'/modules/crm.php';
