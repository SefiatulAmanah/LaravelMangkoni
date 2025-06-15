<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeramalanController extends Controller
{
    public function index(Request $request)
    {
        $produks = DB::table('produks')->get();

        $resultsDES = null;
        $resultsSMA = null;
        $bestAlphaMAE = null;
        $bestAlphaMAPE = null;
        $allAlphaResults = [];

        $selectedAlpha = $request->filled('alpha') ? (float)$request->alpha : null;
        $selectedMethod = $request->metode ?? null;
        $periodeOptions = ['mingguan', 'bulanan'];
        $metodeOptions = ['DES', 'SMA'];
        $tahun = $request->tahun;

        $daftarTahun = DB::table('tb_transaksis')
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        if ($request->filled(['id_produk', 'periode', 'metode'])) {
            $id_produk = $request->id_produk;
            $periode = $request->periode;
            $metode = $request->metode;

            $selectedMethod = $metode;
            $actual = [];

            if ($periode === 'mingguan') {
                $data = DB::table('tb_transaksis')
                    ->select('tanggal', DB::raw('SUM(jumlah) as qty'))
                    ->where('id_produk', $id_produk)
                    ->when($tahun, function ($query) use ($tahun) {
                        $query->whereYear('tanggal', $tahun);
                    })
                    ->groupBy('tanggal')
                    ->orderBy('tanggal')
                    ->get();

                $weeks = [];
                foreach ($data as $row) {
                    $date = Carbon::parse($row->tanggal);
                    $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');

                    if (!isset($weeks[$startOfWeek])) {
                        $weeks[$startOfWeek] = 0;
                    }
                    $weeks[$startOfWeek] += $row->qty;
                }

                ksort($weeks);
                $actual = $weeks;

            } elseif ($periode === 'bulanan') {
                $data = DB::table('tb_transaksis')
                    ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as period, SUM(jumlah) as qty')
                    ->where('id_produk', $id_produk)
                    ->when($tahun, function ($query) use ($tahun) {
                        $query->whereYear('tanggal', $tahun);
                    })
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

                foreach ($data as $row) {
                    $actual[$row->period] = (float)$row->qty;
                }
            }

            $calculateDES = function ($alpha) use ($actual) {
                $S1 = $S2 = $a = $b = $forecast = $error = $mae = $mape = [];
                $periods = array_keys($actual);
                $n = count($periods);
                if ($n < 2) return null;

                $first = $periods[0];
                $S1[$first] = $actual[$first];
                $S2[$first] = $actual[$first];
                $a[$first] = $S1[$first];
                $b[$first] = 0;
                $forecast[$first] = null;
                $error[$first] = null;
                $mae[$first] = null;
                $mape[$first] = null;

                for ($i = 1; $i < $n; $i++) {
                    $t = $periods[$i];
                    $prev = $periods[$i - 1];

                    $S1[$t] = $alpha * $actual[$t] + (1 - $alpha) * $S1[$prev];
                    $S2[$t] = $alpha * $S1[$t] + (1 - $alpha) * $S2[$prev];
                    $a[$t] = 2 * $S1[$t] - $S2[$t];
                    $b[$t] = ($alpha / (1 - $alpha)) * ($S1[$t] - $S2[$t]);
                    $forecast[$t] = $a[$t] + $b[$t];
                    $error[$t] = $actual[$t] - $forecast[$t];
                    $mae[$t] = abs($error[$t]);
                    $mape[$t] = $actual[$t] != 0 ? ($mae[$t] / $actual[$t]) * 100 : null;
                }

                $totalMAE = $totalMAPE = $count = 0;
                for ($i = 1; $i < $n; $i++) {
                    $t = $periods[$i];
                    if (!is_null($mae[$t]) && !is_null($mape[$t])) {
                        $totalMAE += $mae[$t];
                        $totalMAPE += $mape[$t];
                        $count++;
                    }
                }

                return [
                    'actual' => $actual,
                    'forecast' => $forecast,
                    'error' => $error,
                    'MAE' => $count > 0 ? $totalMAE / $count : null,
                    'MAPE' => $count > 0 ? $totalMAPE / $count : null,
                    'alpha' => $alpha,
                    'periods' => $periods,
                    'a' => $a,
                    'b' => $b,
                    'S1' => $S1,
                    'S2' => $S2,
                    'mae_list' => $mae,
                    'mape_list' => $mape,
                ];
            };

            $calculateSMA = function () use ($actual) {
                $window = 3;
                $periods = array_keys($actual);
                $forecast = $error = $percentageErrors = $mae = $mape = $data = [];
                $n = count($periods);

                for ($i = 0; $i < $n; $i++) {
                    $t = $periods[$i];
                    if ($i < $window) {
                        $forecast[$t] = $actual[$t];
                        $error[$t] = 0;
                        $mae[$t] = 0;
                        $percentageErrors[$t] = null;
                        $mape[$t] = null;
                    } else {
                        $sum = 0;
                        for ($j = $i - $window; $j < $i; $j++) {
                            $sum += $actual[$periods[$j]];
                        }
                        $forecast[$t] = $sum / $window;
                        $error[$t] = $actual[$t] - $forecast[$t];
                        $mae[$t] = abs($error[$t]);
                        $mape[$t] = $actual[$t] != 0 ? ($mae[$t] / $actual[$t]) * 100 : null;
                        $percentageErrors[$t] = $mape[$t];

                        $data[] = [
                            'jumlah' => $actual[$t],
                            'forecast' => $forecast[$t],
                            'error' => $error[$t],
                            'mape' => $mape[$t],
                        ];
                    }
                }

                $MAE = $this->hitungMAE($data, 0);
                $MAPE = $this->hitungMAPE($data, 0);

                return [
                    'actual' => $actual,
                    'forecast' => $forecast,
                    'error' => $error,
                    'percentage_error' => $percentageErrors,
                    'MAE' => $MAE,
                    'MAPE' => $MAPE,
                    'periods' => $periods,
                    'window' => $window,
                    'mae_list' => $mae,
                    'mape_list' => $mape,
                ];
            };

            if ($metode === 'DES') {
                $bestAlphaMAE = ['alpha' => null, 'MAE' => INF];
                $bestAlphaMAPE = ['alpha' => null, 'MAPE' => INF];

                for ($i = 1; $i <= 9; $i++) {
                    $alpha = $i / 10;
                    $res = $calculateDES($alpha);
                    if (!$res) continue;

                    $allAlphaResults[] = [
                        'alpha' => $alpha,
                        'MAE' => $res['MAE'],
                        'MAPE' => $res['MAPE'],
                    ];

                    if ($res['MAE'] < $bestAlphaMAE['MAE']) {
                        $bestAlphaMAE = ['alpha' => $alpha, 'MAE' => $res['MAE']];
                    }
                    if ($res['MAPE'] < $bestAlphaMAPE['MAPE']) {
                        $bestAlphaMAPE = ['alpha' => $alpha, 'MAPE' => $res['MAPE']];
                    }
                }

                $resultsDES = $selectedAlpha ? $calculateDES($selectedAlpha) : null;

            } elseif ($metode === 'SMA') {
                $resultsSMA = $calculateSMA();
            }
        }

        return view('peramalan.index', [
            'selectedMethod' => $selectedMethod,
            'resultsDES' => $resultsDES,
            'resultsSMA' => $resultsSMA,
            'bestAlphaMAE' => $bestAlphaMAE,
            'bestAlphaMAPE' => $bestAlphaMAPE,
            'allAlphaResults' => $allAlphaResults,
            'selectedAlpha' => $selectedAlpha,
            'produks' => $produks,
            'periodeOptions' => $periodeOptions,
            'metodeOptions' => $metodeOptions,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun,
        ]);
    }

    private function hitungMAE($data, $offset = 0)
    {
        $total = 0;
        $n = 0;

        for ($i = $offset; $i < count($data); $i++) {
            $d = $data[$i];
            if (!is_null($d['jumlah']) && !is_null($d['forecast'])) {
                $error = abs($d['jumlah'] - $d['forecast']);
                $total += $error;
                $n++;
            }
        }

        return $n > 0 ? $total / $n : 0;
    }

    private function hitungMAPE($data, $offset = 0)
    {
        $total = 0;
        $n = 0;

        for ($i = $offset; $i < count($data); $i++) {
            $d = $data[$i];
            if (!is_null($d['jumlah']) && $d['jumlah'] != 0 && !is_null($d['forecast'])) {
                $error = abs($d['jumlah'] - $d['forecast']);
                $total += ($error / $d['jumlah']) * 100;
                $n++;
            }
        }

        return $n > 0 ? $total / $n : 0;
    }
}