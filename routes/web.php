<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierImportExportController;
use App\Http\Controllers\LayupController;
use App\Http\Controllers\LayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // =========================================================================
    // Dashboard
    // =========================================================================
    Route::get('/dashboard', [SupplierController::class, 'index'])->name('dashboard');

    // =========================================================================
    // Suppliers CRUD
    // =========================================================================
    Route::get('/suppliers/create',          [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers',                [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}',      [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::patch('/suppliers/{supplier}',    [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}',   [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // =========================================================================
    // Supplier Import & Export
    // =========================================================================
    Route::get('/suppliers/{supplier}/export',           [SupplierImportExportController::class, 'export'])->name('suppliers.export');
    Route::post('/suppliers/{supplier}/import',          [SupplierImportExportController::class, 'import'])->name('suppliers.import');

    // Manual conflict resolution (UI-based)
    Route::get('/suppliers/{supplier}/import/preview',   [SupplierImportExportController::class, 'importPreview'])->name('suppliers.import.preview');
    Route::post('/suppliers/{supplier}/import/resolve',  [SupplierImportExportController::class, 'importResolve'])->name('suppliers.import.resolve');

    // =========================================================================
    // Layups CRUD
    // =========================================================================
    Route::get('/suppliers/{supplier}/layups/create', [LayupController::class, 'create'])->name('layups.create');
    Route::post('/suppliers/{supplier}/layups',        [LayupController::class, 'store'])->name('layups.store');
    Route::get('/layups/{layup}',                      [LayupController::class, 'show'])->name('layups.show');
    Route::get('/layups/{layup}/edit',                 [LayupController::class, 'edit'])->name('layups.edit');
    Route::patch('/layups/{layup}',                    [LayupController::class, 'update'])->name('layups.update');
    Route::delete('/layups/{layup}',                   [LayupController::class, 'destroy'])->name('layups.destroy');

    // =========================================================================
    // Layers CRUD
    // =========================================================================
    Route::post('/layups/{layup}/layers',   [LayerController::class, 'store'])->name('layers.store');
    Route::patch('/layers/{layer}',         [LayerController::class, 'update'])->name('layers.update');
    Route::delete('/layers/{layer}',        [LayerController::class, 'destroy'])->name('layers.destroy');

    // =========================================================================
    // Profile (Breeze)
    // =========================================================================
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';