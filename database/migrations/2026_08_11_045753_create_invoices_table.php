<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->date('tanggal');
            $table->string('nama_pelanggan');
            $table->text('alamat_pelanggan')->nullable();
            $table->string('no_so')->nullable(); // No SO/PO
            
            // Perhitungan Uang
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0); // 11% jika ada
            $table->decimal('grand_total', 15, 2)->default(0);
            
            $table->text('terbilang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};