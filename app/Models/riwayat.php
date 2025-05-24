<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class riwayat extends Model
{
    /** @use HasFactory<\Database\Factories\RiwayatFactory> */
    use HasFactory;
    protected $primaryKey = 'id_riwayat'; // Jangan lupa kalau pakai primary key selain 'id'
    public $timestamps = false; // Kalau tabel kamu tidak ada created_at, updated_at

    protected $fillable = [
        'bulan','tanggal', 'id_produk', 'jumlah', 'total'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}