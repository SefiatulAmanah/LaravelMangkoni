<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil periode transaksi terbaru dalam format YYYY-MM
        $latestPeriode = DB::table('tb_transaksis')
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as periode")
            ->orderByDesc('periode')
            ->value('periode');

        // Jika tidak ada data transaksi, fallback ke bulan & tahun saat ini
        if (!$latestPeriode) {
            $latestPeriode = now()->format('Y-m');
        }

        // Format untuk tampilan (contoh: 06-2025)
        $formattedPeriode = date('m-Y', strtotime($latestPeriode . '-01'));

        // Penjualan produk di bulan terbaru
        $penjualanPerBulan = DB::table('tb_transaksis')
            ->join('produks', 'tb_transaksis.id_produk', '=', 'produks.id_produk')
            ->selectRaw("produks.nama_produk, SUM(tb_transaksis.jumlah) as total_terjual")
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$latestPeriode])
            ->groupBy('produks.nama_produk')
            ->orderBy('produks.nama_produk')
            ->get();

        // Stok terbaru per produk (tanpa filter tanggal)
        $stokTerbaruPerProduk = DB::table('stoks')
            ->join('produks', 'stoks.id_produk', '=', 'produks.id_produk')
            ->selectRaw("produks.nama_produk, SUM(stoks.jumlah) as total_stok")
            ->groupBy('produks.nama_produk')
            ->orderBy('produks.nama_produk')
            ->get();

        // Jumlah semua produk
        $jumlahProduk = DB::table('produks')->count();

        // Jumlah transaksi di bulan terbaru
        $jumlahTransaksi = DB::table('tb_transaksis')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$latestPeriode])
            ->count();

        // Jumlah produksi bulan terbaru (gunakan `tanggal_produksi` jika ada)
        $jumlahProduksi = DB::table('produksis')
            ->whereRaw("DATE_FORMAT(COALESCE(tanggal, created_at), '%Y-%m') = ?", [$latestPeriode])
            ->count();

        return view('dashboard', [
            'latestPeriode' => $formattedPeriode,
            'penjualanPerBulan' => $penjualanPerBulan,
            'stokTerbaruPerProduk' => $stokTerbaruPerProduk,
            'jumlahProduksi' => $jumlahProduksi,
            'jumlahTransaksi' => $jumlahTransaksi,
            'jumlahProduk' => $jumlahProduk,
        ]);
    }
}