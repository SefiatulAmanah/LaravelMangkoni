<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class retur extends Model
{
    /** @use HasFactory<\Database\Factories\ReturFactory> */
    use HasFactory;
    protected $primaryKey = 'id_retur';
    protected $table = 'returs';
    protected $fillable = ['id_retur','id_produk','jumlah','keterangan'];

    public function produk()
{
    return $this->belongsTo(Produk::class,'id_produk','id_produk');  // Sesuaikan dengan nama kolom foreign key
}
}