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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('ID_NOTA')->primary();
            $table->date('TGL');
            $table->string('KODE_PELANGGAN');
            $table->integer('SUBTOTAL');

            $table->foreign('KODE_PELANGGAN')
                ->references('ID_PELANGGAN')
                ->on('pelanggan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
