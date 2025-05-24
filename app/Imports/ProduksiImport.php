<?php

namespace App\Imports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;
use App\Models\Produk;
use Illuminate\Support\Str;

class ProduksiImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $tanggal = $row[0];
        $namaProdukExcel = trim($row[1]); // input di Excel adalah nama produk

        // Parsing tanggal (sama seperti sebelumnya)
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

        // Buat slug dari nama produk input Excel
        $slug = Str::slug($namaProdukExcel);

        // Cari produk berdasarkan slug nama_produk (buat slug di query)
        $produk = Produk::all()->first(function ($item) use ($slug) {
            return Str::slug($item->nama_produk) === $slug;
        });

        if (!$produk) {
            throw new \Exception("Produk dengan nama '{$namaProdukExcel}' (slug: {$slug}) tidak ditemukan");
        }

        // Ambil id_produk untuk disimpan
        $id_produk = $produk->id_produk;

        $jumlah = $row[2];

        return new Produksi([
            'tanggal' => $tanggal,
            'id_produk' => $id_produk,
            'jumlah' => $jumlah,
        ]);
    }
}
