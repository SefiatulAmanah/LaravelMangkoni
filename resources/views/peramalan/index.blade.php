@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Peramalan Penjualan Produk</h1>

    <form action="{{ url()->current() }}" method="GET" class="mb-4">
        <div class="row g-3 align-items-center">

            <div class="col-auto">
                <label for="id_produk" class="form-label">Produk</label>
                <select name="id_produk" id="id_produk" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                    <option value="{{ $produk->id_produk }}"
                        {{ (request('id_produk') == $produk->id_produk) ? 'selected' : '' }}>
                        {{ $produk->nama_produk ?? $produk->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label for="periode" class="form-label">Periode</label>
                <select name="periode" id="periode" class="form-select" required>
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periodeOptions as $opt)
                    <option value="{{ $opt }}" {{ (request('periode') == $opt) ? 'selected' : '' }}>
                        {{ ucfirst($opt) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- Tambahan filter Tahun -->
            <div class="col-auto">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($daftarTahun as $th)
                    <option value="{{ $th }}" {{ (request('tahun') == $th) ? 'selected' : '' }}>
                        {{ $th }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label for="metode" class="form-label">Metode</label>
                <select name="metode" id="metode" class="form-select" required>
                    <option value="">-- Pilih Metode --</option>
                    @foreach($metodeOptions as $opt)
                    <option value="{{ $opt }}" {{ (request('metode') == $opt) ? 'selected' : '' }}>
                        {{ $opt }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto" id="alphaDiv" style="display: none;">
                <label for="alpha" class="form-label">Alpha (0.1 - 0.9)</label>
                <select name="alpha" id="alpha" class="form-select">
                    <option value="">-- Pilih Alpha --</option>
                    @for ($i = 1; $i <= 9; $i++) <option value="{{ $i / 10 }}"
                        {{ (number_format(request('alpha'), 1) == $i/10) ? 'selected' : '' }}>
                        {{ $i / 10 }}
                        </option>
                        @endfor
                </select>
            </div>

            <div class="col-auto align-self-end">
                <button type="submit" class="btn btn-dark">Hitung</button>
            </div>
        </div>
    </form>

    <script>
    function toggleAlpha() {
        const metode = document.getElementById('metode').value;
        const alphaDiv = document.getElementById('alphaDiv');
        if (metode === 'DES') {
            alphaDiv.style.display = 'block';
        } else {
            alphaDiv.style.display = 'none';
            document.getElementById('alpha').value = '';
        }
    }

    document.getElementById('metode').addEventListener('change', toggleAlpha);
    window.onload = toggleAlpha;
    </script>

    @if($selectedMethod === 'DES' && !empty($resultsDES))
    <h3>Hasil Peramalan Double Exponential Smoothing (Alpha = {{ $selectedAlpha }})</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Aktual</th>
                <th>S1</th>
                <th>S2</th>
                <th>At</th>
                <th>Bt</th>
                <th>Peramalan (Ft)</th>
                <th>Error</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultsDES['periods'] as $p)
            <tr>
                <td>{{ $p }}</td>
                <td>{{ isset($resultsDES['actual'][$p]) ? $resultsDES['actual'][$p] : '-' }}</td>
                <td>{{ isset($resultsDES['S1'][$p]) ? number_format($resultsDES['S1'][$p], 2) : '-' }}</td>
                <td>{{ isset($resultsDES['S2'][$p]) ? number_format($resultsDES['S2'][$p], 2) : '-' }}</td>
                <td>{{ isset($resultsDES['a'][$p]) ? number_format($resultsDES['a'][$p], 2) : '-' }}</td>
                <td>{{ isset($resultsDES['b'][$p]) ? number_format($resultsDES['b'][$p], 2) : '-' }}</td>
                <td>{{ isset($resultsDES['forecast'][$p]) ? number_format($resultsDES['forecast'][$p], 2) : '-' }}</td>
                <td>{{ isset($resultsDES['error'][$p]) ? number_format($resultsDES['error'][$p], 2) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>MAE:</strong> {{ isset($resultsDES['MAE']) ? number_format($resultsDES['MAE'], 4) : '-' }}</p>
    <p><strong>MAPE:</strong> {{ isset($resultsDES['MAPE']) ? number_format($resultsDES['MAPE'], 4) . '%' : '-' }}</p>

    <hr>

    <h4>Evaluasi Semua Alpha (0.1 s.d 0.9)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alpha</th>
                <th>MAE</th>
                <th>MAPE (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allAlphaResults as $alphaRes)
            <tr>
                <td>{{ $alphaRes['alpha'] }}</td>
                <td>{{ number_format($alphaRes['MAE'], 4) }}</td>
                <td>{{ number_format($alphaRes['MAPE'], 4) }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <h5>
        Alpha Terbaik berdasarkan MAE:
        <span class="text-success">{{ $bestAlphaMAE['alpha'] ?? '-' }}</span>
        (MAE: {{ isset($bestAlphaMAE['MAE']) ? number_format($bestAlphaMAE['MAE'], 4) : '-' }})
    </h5>
    <h5>
        Alpha Terbaik berdasarkan MAPE:
        <span class="text-success">{{ $bestAlphaMAPE['alpha'] ?? '-' }}</span>
        (MAPE: {{ isset($bestAlphaMAPE['MAPE']) ? number_format($bestAlphaMAPE['MAPE'], 4) . '%' : '-' }})
    </h5>
    @endif


    @if($selectedMethod === 'SMA' && $resultsSMA)
    <h3>Hasil Peramalan Single Moving Average SMA (Window 3)</h3>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Periode</th>
                <th>Aktual</th>
                <th>Peramalan</th>
                <th>Error</th>
                <th>MAE</th>
                <th>MAPE (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultsSMA['periods'] as $p)
            <tr>
                <td>{{ $p }}</td>
                <td>{{ $resultsSMA['actual'][$p] }}</td>
                <td>
                    @if(isset($resultsSMA['forecast'][$p]))
                    {{ number_format($resultsSMA['forecast'][$p], 2) }}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if(isset($resultsSMA['error'][$p]))
                    {{ number_format($resultsSMA['error'][$p], 2) }}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if(isset($resultsSMA['mae_list'][$p]))
                    {{ number_format($resultsSMA['mae_list'][$p], 2) }}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if(isset($resultsSMA['percentage_error'][$p]))
                    {{ number_format($resultsSMA['percentage_error'][$p], 2) }}
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total MAE:</strong> {{ number_format($resultsSMA['MAE'], 4) }}</p>
    <p><strong>Total MAPE:</strong> {{ number_format($resultsSMA['MAPE'], 4) }}%</p>
    @endif

    @if( ($selectedMethod === 'DES' && $resultsDES) || ($selectedMethod === 'SMA' && $resultsSMA) )
    <div class="mt-5">
        <h3>Grafik Peramalan dan Data Aktual</h3>
        <canvas id="forecastChart" height="100"></canvas>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        window.onload = function() {
            const labels = @json($selectedMethod === 'DES' ? $resultsDES['periods'] : $resultsSMA[
                'periods']);
            const actualData = @json($selectedMethod === 'DES' ? array_values($resultsDES['actual']) :
                array_values(
                    $resultsSMA['actual']));
            const forecastData = @json($selectedMethod === 'DES' ? array_values($resultsDES['forecast']) :
                array_values($resultsSMA['forecast']));


            const ctx = document.getElementById('forecastChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Data Aktual',
                            data: actualData,
                            borderColor: 'black',
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: 'Peramalan',
                            data: forecastData,
                            borderColor: 'grey',
                            fill: false,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        };
        </script>
    </div>
    @endif
    @php
    $isBulanan = (request('periode') == 'bulanan');
    @endphp

    @if($selectedMethod === 'DES' && !empty($resultsDES))
    @php
    $lastPeriod = end($resultsDES['periods']);
    $lastA = end($resultsDES['a']);
    $lastB = end($resultsDES['b']);
    $futureForecasts = [];

    for($m = 1; $m <= 4; $m++) { $nextPeriod=$isBulanan ? \Carbon\Carbon::parse($lastPeriod)->
        addMonthsNoOverflow($m)->format('Y-m')
        : \Carbon\Carbon::parse($lastPeriod)->addWeeks($m)->format('Y-m-d');

        $forecast = $lastA + $lastB * $m;

        $futureForecasts[] = [
        'periode' => $nextPeriod,
        'aktual' => 0,
        'forecast' => $forecast
        ];
        }
        @endphp
        <h4> </h4>
        <h4>Peramalan {{ $isBulanan ? '4 Bulan' : '4 Minggu' }} ke Depan (DES)</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Aktual</th>
                    <th>Peramalan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($futureForecasts as $f)
                <tr>
                    <td>{{ $f['periode'] }}</td>
                    <td>{{ $f['aktual'] }}</td>
                    <td>{{ number_format($f['forecast'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($selectedMethod === 'SMA' && !empty($resultsSMA))
        @php
        $lastPeriod = end($resultsSMA['periods']);
        $futureForecastsSMA = [];

        // Ambil forecast terakhir untuk diasumsikan sebagai forecast mendatang
        $lastForecast = end($resultsSMA['forecast']) ?? 0;

        for($m = 1; $m <= 4; $m++) { $nextPeriod=$isBulanan ? \Carbon\Carbon::parse($lastPeriod)->
            addMonthsNoOverflow($m)->format('Y-m')
            : \Carbon\Carbon::parse($lastPeriod)->addWeeks($m)->format('Y-m-d');

            $futureForecastsSMA[] = [
            'periode' => $nextPeriod,
            'aktual' => 0,
            'forecast' => $lastForecast
            ];
            }
            @endphp

            <h4>Peramalan {{ $isBulanan ? '4 Bulan' : '4 Minggu' }} ke Depan (SMA)</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Aktual</th>
                        <th>Peramalan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($futureForecastsSMA as $f)
                    <tr>
                        <td>{{ $f['periode'] }}</td>
                        <td>{{ $f['aktual'] }}</td>
                        <td>{{ number_format($f['forecast'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
</div>
@endsection