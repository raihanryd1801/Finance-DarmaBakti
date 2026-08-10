<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UangMasukController;

Route::get('/', [UangMasukController::class, 'index'])->name('home');

// Atau jika tetap ingin mempertahankan path URL-nya, Abang bisa pakai Redirect:
// Route::get('/', function () {
//     return redirect()->route('uang_masuk.index');
// });

Route::get('/finance/uang-masuk', [UangMasukController::class, 'index'])->name('uang_masuk.index');
// Menampilkan halaman form tambah data
Route::get('/finance/uang-masuk/create', [UangMasukController::class, 'create'])->name('uang_masuk.create');

// Memproses data yang disubmit dari form
Route::post('/finance/uang-masuk', [UangMasukController::class, 'store'])->name('uang_masuk.store');
Route::post('/finance/uang-masuk/import', [UangMasukController::class, 'import'])->name('uang_masuk.import');