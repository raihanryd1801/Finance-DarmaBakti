<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->decimal('harga', 15, 2); // Pakai decimal untuk antisipasi harga besar/koma
            $table->string('satuan'); // Pcs, Stel, Rim, dll
            $table->text('keterangan')->nullable(); // Boleh kosong
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};