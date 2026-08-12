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
    Route::get('/darmapp/report', [UangMasukController::class, 'report'])->name('finance.report');

    // Keuangan / Uang Masuk
    Route::get('/darmapp/uang-masuk', [UangMasukController::class, 'index'])->name('uang_masuk.index');
    Route::get('/darmapp/uang-masuk/create', [UangMasukController::class, 'create'])->name('uang_masuk.create');
    Route::post('/darmapp/uang-masuk', [UangMasukController::class, 'store'])->name('uang_masuk.store');
    Route::post('/darmappuang-masuk/import', [UangMasukController::class, 'import'])->name('uang_masuk.import');

    

    // Edit, Update, Delete
    Route::get('/darmapp/uang-masuk/{id}/edit', [UangMasukController::class, 'edit'])->name('uang_masuk.edit');
    Route::put('/darmapp/uang-masuk/{id}', [UangMasukController::class, 'update'])->name('uang_masuk.update');
    Route::delete('/darmapp/uang-masuk/{id}', [UangMasukController::class, 'destroy'])->name('uang_masuk.destroy');

    // API DOKUMEN (Pastikan ada ->name('dokumen-api.index'))
    // API DOKUMEN (Dengan prefix /finance/)
    Route::get('/darmapp/dokumen-api', [DokumenApiController::class, 'index'])->name('dokumen-api.index');
    Route::get('/darmapp/dokumen-api/{id}/preview', [DokumenApiController::class, 'preview'])->name('dokumen-api.preview');
    Route::get('/darmapp/dokumen-api/{id}/download', [DokumenApiController::class, 'download'])->name('dokumen-api.download');

    // --- MODULE INVOICE PENJUALAN ---
    Route::get('/darmapp/invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/darmapp/invoice/create', [App\Http\Controllers\InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/darmapp/invoice/store', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/darmapp//invoice/{id}/print', [App\Http\Controllers\InvoiceController::class, 'print'])->name('invoice.print');
    //LUNAS
    Route::post('/darmapp//invoice/{id}/lunas/{kategori}', [App\Http\Controllers\InvoiceController::class, 'tandaiLunas'])->name('invoice.lunas');
    Route::get('/darmapp//invoice/{id}/edit', [App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::post('/darmapp//invoice/{id}/update', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoice.update');
    Route::post('/darmapp//invoice/{id}/destroy', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoice.destroy');

    // --- MODULE MASTER DATA BARANG ---
    Route::get('/darmapp/barang', [App\Http\Controllers\BarangController::class, 'index'])->name('barang.index');
    Route::get('/darmapp/barang/create', [App\Http\Controllers\BarangController::class, 'create'])->name('barang.create');
    Route::post('/darmapp/store', [App\Http\Controllers\BarangController::class, 'store'])->name('barang.store');
    Route::get('/darmapp/barang/{id}/edit', [App\Http\Controllers\BarangController::class, 'edit'])->name('barang.edit');
    Route::post('/darmapp/barang/{id}/update', [App\Http\Controllers\BarangController::class, 'update'])->name('barang.update');
    Route::post('/darmapp/barang/{id}/destroy', [App\Http\Controllers\BarangController::class, 'destroy'])->name('barang.destroy');

    // --- MODULE MASTER DATA REKENING ---
    Route::get('/darmapp/rekening', [App\Http\Controllers\RekeningController::class, 'index'])->name('rekening.index');
    Route::get('/darmapp/rekening/create', [App\Http\Controllers\RekeningController::class, 'create'])->name('rekening.create');
    Route::post('/darmapp/rekening/store', [App\Http\Controllers\RekeningController::class, 'store'])->name('rekening.store');
    Route::get('/darmapp/rekening/{id}/edit', [App\Http\Controllers\RekeningController::class, 'edit'])->name('rekening.edit');
    Route::post('/darmapp/rekening/{id}/update', [App\Http\Controllers\RekeningController::class, 'update'])->name('rekening.update');
    Route::post('/darmapp/rekening/{id}/destroy', [App\Http\Controllers\RekeningController::class, 'destroy'])->name('rekening.destroy');
    
    //USER CONTROLLER
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});