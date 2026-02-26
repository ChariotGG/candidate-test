<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierImportExportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LayupController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
Route::get('/layups/{layup}', [LayupController::class, 'show'])->name('layups.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route untuk Export (GET)
    Route::get('/suppliers/{supplier}/export', [SupplierImportExportController::class, 'export'])
         ->name('suppliers.export');

    // Route untuk Import (POST)
    Route::post('/suppliers/{supplier}/import', [SupplierImportExportController::class, 'import'])
         ->name('suppliers.import');
});

require __DIR__.'/auth.php';
