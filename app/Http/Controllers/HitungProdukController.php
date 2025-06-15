<?php

namespace App\Http\Controllers;

use App\Models\tb_transaksi;
use Illuminate\Http\Request;
use App\Models\Produk;
use Carbon\Carbon;

class HitungProdukController extends Controller
{
    public function index(Request $request)
    {
        $id_produk = $request->input('id_produk');
        $selectedTahun = $request->input('tahun');

        $produkList = Produk::all();

        // Ambil daftar tahun dari data transaksi
        $tahunList = tb_transaksi::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        $data = [];

        if ($id_produk && $selectedTahun) {
            $transaksi = tb_transaksi::where('id_produk', $id_produk)
                ->whereYear('tanggal', $selectedTahun)
                ->get();

            // Group by week (awal minggu)
            $perMinggu = $transaksi->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->startOfWeek()->format('Y-m-d');
            })->map(function ($group) {
                return $group->sum('jumlah');
            });

            // Group by month (format Y-m)
            $perBulan = $transaksi->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m');
            })->map(function ($group) {
                return $group->sum('jumlah');
            });

            $data['perMinggu'] = $perMinggu;
            $data['perBulan'] = $perBulan;
        }

        return view('hitungproduk', [
            'produkList' => $produkList,
            'selectedProduk' => $id_produk,
            'tahunList' => $tahunList,
            'selectedTahun' => $selectedTahun,
            'data' => $data
        ]);
    }
        public function cetak(Request $request)
    {
        $id_produk = $request->input('id_produk');
        $selectedTahun = $request->input('tahun');
        $produk = Produk::find($id_produk);

        $transaksi = tb_transaksi::where('id_produk', $id_produk)
            ->whereYear('tanggal', $selectedTahun)
            ->get();

        $perMinggu = $transaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->startOfWeek()->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('jumlah');
        });

        $perBulan = $transaksi->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m');
        })->map(function ($group) {
            return $group->sum('jumlah');
        });

        $total = $perMinggu->sum();

        return view('hitungproduk_cetak', [
            'produk' => $produk,
            'tahun' => $selectedTahun,
            'perMinggu' => $perMinggu,
            'perBulan' => $perBulan,
            'total' => $total
        ]);
    }
}