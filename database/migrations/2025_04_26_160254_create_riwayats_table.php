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
        Schema::create('riwayats', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->string('bulan');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id_produk')->on('produks');
            $table->string('jumlah');
            $table->string('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};