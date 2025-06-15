<?php

namespace App\Imports;

use App\Models\tb_transaksi;
use App\Models\Produk;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TransaksiImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua (abaikan header)
    }

    public function model(array $row)
    {   
         if (empty(array_filter($row))) {
        return null;
    }
        $tanggal = $row[0];
        $namaProdukExcel = trim($row[1]); // Nama produk dari Excel
        $jumlah = $row[2];
        $total = $row[3];

        // Parsing tanggal
        if (is_numeric($tanggal)) {
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal);
        } else {
            try {
                $tanggal = Carbon::createFromFormat('d/m/Y', $tanggal);
            } catch (\Exception $e) {
                try {
                    $tanggal = Carbon::parse($tanggal);
                } catch (\Exception $e) {
                    $tanggal = null;
                }
            }
        }

        if (!$tanggal) {
            throw new \Exception('Invalid date format in row: ' . json_encode($row));
        }

        // Buat slug dari nama produk untuk pencocokan
        $slug = Str::slug($namaProdukExcel);

        // Cari produk berdasarkan slug
        $produk = Produk::all()->first(function ($item) use ($slug) {
            return Str::slug($item->nama_produk) === $slug;
        });

if (empty($namaProdukExcel)) {
    throw new \Exception("Nama produk kosong pada baris: " . json_encode($row));
}

        $id_produk = $produk->id_produk;

        return new tb_transaksi([
            'tanggal' => $tanggal instanceof \DateTime ? $tanggal->format('Y-m-d') : $tanggal,
            'id_produk' => $id_produk,
            'jumlah' => $jumlah,
            'total' => $total,
        ]);
    }
}