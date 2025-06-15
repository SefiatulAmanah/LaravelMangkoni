<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class ProdukImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Lewati baris header
    }

    public function model(array $row)
    {
        $namaProdukExcel = trim($row[0]); // Ambil nama produk dari Excel
        $harga = $row[1]; // Ambil harga dari Excel

        // Buat slug dari nama produk Excel
        $slug = Str::slug($namaProdukExcel);

        // Cek apakah produk dengan slug yang sama sudah ada
        $existing = Produk::all()->first(function ($item) use ($slug) {
            return Str::slug($item->nama_produk) === $slug;
        });

        if ($existing) {
            // Produk sudah ada, lewati (atau bisa di-update jika diperlukan)
            return null;
        }

        return new Produk([
            'nama_produk' => $namaProdukExcel,
            'harga' => $harga,
        ]);
    }
}
