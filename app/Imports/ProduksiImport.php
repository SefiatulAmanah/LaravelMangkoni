<?php

namespace App\Imports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProduksiImport implements ToModel
{
    public function model(array $row)
    {
        return new Produksi([
            'hari' => $row[0],
            'tanggal' => $row[1],
            'id_produk' => $row[2],
            'jumlah' => $row[3],
        ]);
    }
}