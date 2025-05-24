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
    Schema::table('tb_transaksis', function (Blueprint $table) {
        $table->decimal('total', 15, 2)->after('jumlah'); // Menambahkan kolom 'total'
    });
}

public function down()
{
    Schema::table('tb_transaksis', function (Blueprint $table) {
        $table->dropColumn('total');
    });
}

};