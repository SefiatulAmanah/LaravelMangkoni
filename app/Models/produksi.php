<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produksi extends Model
{
    /** @use HasFactory<\Database\Factories\ProduksiFactory> */
    use HasFactory;
    protected $primaryKey = 'id_produksi';
    protected $table = 'produksis';
    protected $fillable = ['id_produksi','tanggal','id_produk','jumlah'];

    public function produk()
{
    return $this->belongsTo(Produk::class,'id_produk','id_produk');  // Sesuaikan dengan nama kolom foreign key
}
    public function stoks(){

    return $this->hasMany(stok::class);
}
}
