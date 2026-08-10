<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::table('uang_masuk', function (Blueprint $table) {
        $table->string('kategori')->default('pemerintah')->after('tanggal_transfer');
        $table->string('keterangan')->nullable();
        $table->decimal('nilai_nota', 15, 2)->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uang_masuk', function (Blueprint $table) {
            //
        });
    }
};
