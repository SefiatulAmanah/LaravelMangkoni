<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
        public function index()
    {
        // Ambil periode terbaru dari transaksi (format YYYY-MM)
        $latestPeriode = DB::table('tb_transaksis')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as periode")
            ->orderBy('periode', 'desc')
            ->limit(1)
            ->value('periode');

        // Jika tidak ada transaksi, gunakan bulan & tahun sekarang
        if (!$latestPeriode) {
            $latestPeriode = date('Y-m');
        }

        // Ambil data penjualan produk untuk periode terbaru
        $penjualanPerBulan = DB::table('tb_transaksis')
            ->join('produks', 'tb_transaksis.id_produk', '=', 'produks.id_produk')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as periode, produks.nama_produk, SUM(tb_transaksis.jumlah) as total_terjual")
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$latestPeriode])
            ->groupBy('periode', 'produks.nama_produk')
            ->orderBy('produks.nama_produk')
            ->get();

        // Ambil jumlah stok terakhir per produk (tanpa filter tanggal)
        $stokTerbaruPerProduk = DB::table('stoks')
            ->join('produks', 'stoks.id_produk', '=', 'produks.id_produk')
            ->selectRaw("produks.nama_produk, SUM(stoks.jumlah) as total_stok")
            ->groupBy('produks.nama_produk')
            ->orderBy('produks.nama_produk')
            ->get();

        // Format periode menjadi MM-YYYY untuk ditampilkan
        $formattedPeriode = date('m-Y', strtotime($latestPeriode . '-01'));

        // Kirim semua data ke view
        return view('dashboard', [
            'latestPeriode' => $formattedPeriode,
            'penjualanPerBulan' => $penjualanPerBulan,
            'stokTerbaruPerProduk' => $stokTerbaruPerProduk,
        ]);
    }
}