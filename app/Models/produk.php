<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukFactory> */
    use HasFactory;
    protected $primaryKey = 'id_produk';
    protected $table = 'produks';
    public $timestamps = false;
    protected $fillable = ['nama_produk','harga'];
    use HasFactory;

    public function produk()
    {
        return $this->belongsTo(produk::class, 'id_produk','id_produk');
    }
    public function produksis(){
        return $this->hasMany(Produksi::class, 'id_produk'); // ← ini penting
    }
    public function tb_transaksis(){
        return $this->hasMany(tb_transaksi::class,'id_produk', 'id_produk');
    }
    public function riwayat(){
        return $this->hasMany(riwayat::class,'id_produk', 'id_produk');
    }
    public function retur(){
        return $this->hasMany(riwayat::class,'id_produk', 'id_produk');
    }
    public function stok()
    {
        return $this->hasOne(Stok::class, 'id_produk');
    }
    public function getStockTerbaruAttribute()
    {
        $produksi = $this->produksis()->sum('jumlah');
        $penjualan = $this->tb_transaksis()->sum('jumlah');
        return $produksi - $penjualan;
    }
}