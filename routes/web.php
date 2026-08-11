<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UangMasukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenApiController;

// --- RUTE GUEST (Bebas diakses tanpa login) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// --- RUTE PROTECTED (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    // Halaman Utama / Home
    Route::get('/', [UangMasukController::class, 'index'])->name('home');

    // Report
    Route::get('/finance/report', [UangMasukController::class, 'report'])->name('finance.report');

    // Keuangan / Uang Masuk
    Route::get('/finance/uang-masuk', [UangMasukController::class, 'index'])->name('uang_masuk.index');
    Route::get('/finance/uang-masuk/create', [UangMasukController::class, 'create'])->name('uang_masuk.create');
    Route::post('/finance/uang-masuk', [UangMasukController::class, 'store'])->name('uang_masuk.store');
    Route::post('/finance/uang-masuk/import', [UangMasukController::class, 'import'])->name('uang_masuk.import');

    

    // Edit, Update, Delete
    Route::get('/finance/uang-masuk/{id}/edit', [UangMasukController::class, 'edit'])->name('uang_masuk.edit');
    Route::put('/finance/uang-masuk/{id}', [UangMasukController::class, 'update'])->name('uang_masuk.update');
    Route::delete('/finance/uang-masuk/{id}', [UangMasukController::class, 'destroy'])->name('uang_masuk.destroy');

    // API DOKUMEN (Pastikan ada ->name('dokumen-api.index'))
    // API DOKUMEN (Dengan prefix /finance/)
    Route::get('/finance/dokumen-api', [DokumenApiController::class, 'index'])->name('dokumen-api.index');
    Route::get('/finance/dokumen-api/{id}/preview', [DokumenApiController::class, 'preview'])->name('dokumen-api.preview');
    Route::get('/finance/dokumen-api/{id}/download', [DokumenApiController::class, 'download'])->name('dokumen-api.download');

    // --- MODULE INVOICE PENJUALAN ---
    Route::get('/darma/invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/darma/invoice/create', [App\Http\Controllers\InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/darma/invoice/store', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/darma/invoice/{id}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoice.print');
    //LUNAS
    Route::post('/darma/invoice/{id}/lunas/{kategori}', [App\Http\Controllers\InvoiceController::class, 'tandaiLunas'])->name('invoice.lunas');
    Route::get('/darma/invoice/{id}/edit', [App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::post('/darma/invoice/{id}/update', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoice.update');
    Route::post('/darma/invoice/{id}/destroy', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoice.destroy');

    // --- MODULE MASTER DATA BARANG ---
    Route::get('/darma/barang', [App\Http\Controllers\BarangController::class, 'index'])->name('barang.index');
    Route::get('/darma/create', [App\Http\Controllers\BarangController::class, 'create'])->name('barang.create');
    Route::post('/darma/store', [App\Http\Controllers\BarangController::class, 'store'])->name('barang.store');
    Route::get('/darma/{id}/edit', [App\Http\Controllers\BarangController::class, 'edit'])->name('barang.edit');
    Route::post('/darma/{id}/update', [App\Http\Controllers\BarangController::class, 'update'])->name('barang.update');
    Route::post('/darma/{id}/destroy', [App\Http\Controllers\BarangController::class, 'destroy'])->name('barang.destroy');

    
    

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});