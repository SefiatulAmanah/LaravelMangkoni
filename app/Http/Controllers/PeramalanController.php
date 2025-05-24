<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\tb_transaksi;
use Carbon\Carbon;

class PeramalanController extends Controller
{
    public function index(Request $request)
    {
        $produkList = Produk::all();
        $tipe = $request->input('tipe', 'minggu');
        $id_produk = $request->input('produk_id');
        $tahun = $request->input('tahun', date('Y'));
        $periode = $request->input('periode', 1);
        $alpha = $request->input('alpha', 0.5);

        $tb_transaksi = collect();
        $hasilSMA = [];
        $hasilDES = [];

        if ($id_produk) {
            if ($tipe == 'minggu') {
                $start = Carbon::now()->setISODate($tahun, $periode)->startOfWeek();
                $end = Carbon::now()->setISODate($tahun, $periode)->endOfWeek();
            } else { // tipe bulan
                $start = Carbon::createFromDate($tahun, $periode, 1)->startOfMonth();
                $end = Carbon::createFromDate($tahun, $periode, 1)->endOfMonth();
            }

            $tb_transaksi = tb_transaksi::where('produk_id', $id_produk)
                ->whereBetween('tanggal', [$start, $end])
                ->orderBy('tanggal', 'asc')
                ->get();

            $data = $tb_transaksi->pluck('jumlah')->toArray();

            $hasilSMA = $this->hitungSMA($data, 3);
            $hasilDES = $this->hitungDES($data, $alpha);
        }

        return view('peramalan.index', compact(
            'produkList', 'tipe', 'id_produk', 'tahun', 'periode', 'alpha', 'tb_transaksi', 'hasilSMA', 'hasilDES'
        ));
    }

    private function hitungSMA($data, $window)
    {
        $sma = [];
        for ($i = 0; $i < count($data); $i++) {
            if ($i + 1 >= $window) {
                $sum = 0;
                for ($j = $i; $j > $i - $window; $j--) {
                    $sum += $data[$j];
                }
                $sma[] = $sum / $window;
            } else {
                $sma[] = null;
            }
        }
        return $sma;
    }

    private function hitungDES($data, $alpha)
    {
        $des = [];
        $s1 = $s2 = null;
        foreach ($data as $i => $actual) {
            if ($i == 0) {
                $s1 = $actual;
                $s2 = $actual;
                $des[] = $actual;
            } else {
                $s1 = $alpha * $actual + (1 - $alpha) * $s1;
                $s2 = $alpha * $s1 + (1 - $alpha) * $s2;
                $at = 2 * $s1 - $s2;
                $bt = ($alpha / (1 - $alpha)) * ($s1 - $s2);
                $des[] = $at + $bt;
            }
        }
        return $des;
    }
}