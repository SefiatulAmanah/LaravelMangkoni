<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_transaksi extends Model
{
    /** @use HasFactory<\Database\Factories\TbTransaksiFactory> */
    use HasFactory;
    protected $primaryKey = 'id_transaksi'; // Jangan lupa kalau pakai primary key selain 'id'
    public $timestamps = false; // Kalau tabel kamu tidak ada created_at, updated_at

    protected $fillable = [
        'tanggal', 'id_produk', 'jumlah', 'total'
    ];

    // model tb_transaksi
public function produk()
{
    return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
}

public function stoks(){
    return $this->belongsTo(stok::class, 'id_produk', 'id_produk');
}

}