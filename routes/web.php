<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CRM\CustomerController;
use App\Http\Controllers\CRM\CompanyController;
use App\Http\Controllers\CRM\TaskController;
use App\Http\Controllers\CRM\TaskTemplateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FolderController;

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

// Route::middleware('auth')->group(function () {
//     Route::resource('crm/customers', CustomerController::class);
//     Route::resource('crm/companies', CompanyController::class);
// });

// Wrap document and folder routes in auth middleware
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
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');

    // Folder routes
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/'); // Redirect to home or login page after logout
})->name('logout');

require __DIR__.'/auth.php';
require __DIR__.'/modules/crm.php';



