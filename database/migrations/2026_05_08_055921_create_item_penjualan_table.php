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
        Schema::create('item_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('NOTA');
            $table->string('KODE_BARANG');
            $table->integer('QTY');

            $table->foreign('NOTA')
                ->references('ID_NOTA')
                ->on('penjualan')
                ->cascadeOnDelete();

            $table->foreign('KODE_BARANG')
                ->references('KODE')
                ->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penjualan');
    }
};
