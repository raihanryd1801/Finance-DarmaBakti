<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UangMasukController;
use App\Http\Controllers\AuthController;

// --- RUTE GUEST (Bebas diakses tanpa login) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// --- RUTE PROTECTED (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    // Halaman Utama / Home
    Route::get('/', [UangMasukController::class, 'index'])->name('home');

    // Keuangan / Uang Masuk
    Route::get('/finance/uang-masuk', [UangMasukController::class, 'index'])->name('uang_masuk.index');
    Route::get('/finance/uang-masuk/create', [UangMasukController::class, 'create'])->name('uang_masuk.create');
    Route::post('/finance/uang-masuk', [UangMasukController::class, 'store'])->name('uang_masuk.store');
    Route::post('/finance/uang-masuk/import', [UangMasukController::class, 'import'])->name('uang_masuk.import');

    // Report
    Route::get('/finance/report', [UangMasukController::class, 'report'])->name('finance.report');

    // Edit, Update, Delete
    Route::get('/finance/uang-masuk/{id}/edit', [UangMasukController::class, 'edit'])->name('uang_masuk.edit');
    Route::put('/finance/uang-masuk/{id}', [UangMasukController::class, 'update'])->name('uang_masuk.update');
    Route::delete('/finance/uang-masuk/{id}', [UangMasukController::class, 'destroy'])->name('uang_masuk.destroy');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});