<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_stok';
    protected $table = 'stoks';
    protected $fillable = ['id_produk', 'jumlah'];

    // Relasi ke tabel produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    // Relasi ke produksi berdasarkan id_produk
    public function produks()
    {
        return $this->hasMany(Produksi::class, 'id_produk', 'id_produk');
    }

    // Relasi ke transaksi berdasarkan id_produk
    public function transaksis()
    {
        return $this->hasMany(tb_transaksi::class, 'id_produk', 'id_produk');
    }

    // Accessor untuk stok akhir
    public function getStokAkhirAttribute()
    {
        return $this->produks->sum('jumlah') - $this->tb_transaksis->sum('jumlah');
    }
}