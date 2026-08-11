<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel invoices
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            
            $table->string('nama_barang');
            $table->integer('qty');
            $table->string('satuan'); // Pcs, Stel, dll
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2); // qty * harga
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};