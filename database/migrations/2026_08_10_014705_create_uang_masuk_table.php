<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uang_masuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transfer');
            $table->string('instansi'); // Contoh: BANTEN, ACEH, SWASTA, PELAYARAN
            $table->string('nama_pengadaan')->nullable();
            $table->string('nama_ppk')->nullable();
            
            // Metrik Keuangan
            $table->decimal('jumlah_include_ppn', 15, 2)->default(0);
            $table->decimal('jumlah_exclude_ppn', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('pph_22', 15, 2)->default(0);
            $table->decimal('total_diterima', 15, 2)->default(0);
            
            // Rekonsiliasi
            $table->decimal('total_rekening_koran', 15, 2)->nullable();
            $table->decimal('selisih', 15, 2)->nullable();
            
            // Status dan Keterangan
            $table->string('status_transfer')->default('BELUM'); // SUDAH/BELUM
            $table->string('rekening_tujuan')->nullable(); // DARMA, LINTANG, dll
            $table->string('status_pengembalian')->nullable(); 
            $table->string('status_faktur')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uang_masuk');
    }
};