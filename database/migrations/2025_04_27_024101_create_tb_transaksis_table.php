<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('tb_transaksis', function (Blueprint $table) {
        $table->id('id_transaksi');
        $table->date('tanggal');
        $table->unsignedBigInteger('id_produk');
        $table->foreign('id_produk')->references('id_produk')->on('produks');
        $table->integer('jumlah');
        $table->decimal('total', 15, 2);
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('tb_transaksis');
}

};