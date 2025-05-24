@extends('layouts.app')

@section('content')
<div class="container-lg py-4 mx-auto" style="max-width: 1200px;">
    @if(session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Login Berhasil',
        html: `Selamat datang, <strong>{{ session('user_name') }}</strong>!`,
        timer: 3500,
        timerProgressBar: true,
        showConfirmButton: false,
        customClass: {
            title: 'swal-title',
            htmlContainer: 'swal-html'
        }
    });
    </script>
    <style>
    .swal-title {
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 0.5em;
    }

    .swal-html {
        color: #444;
        font-size: 1.1rem;
    }
    </style>
    @endif

    <h2 class="text-center mb-4">📊 Grafik Penjualan Produk Bulan Terbaru ({{ $latestPeriode }})</h2>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body">
            <canvas id="penjualanChart" height="100"></canvas>
        </div>
    </div>

    <h2 class="text-center mb-4">📦 Grafik Stok Produk Saat Ini</h2>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body">
            <canvas id="stokChart" height="100"></canvas>
        </div>
    </div>
</div>

@php
$produk = $penjualanPerBulan->pluck('nama_produk')->values();
$produkStok = $stokTerbaruPerProduk->pluck('nama_produk')->values();

$palette = [
'rgba(255, 99, 132, 0.8)',
'rgba(54, 162, 235, 0.8)',
'rgba(255, 206, 86, 0.8)',
'rgba(75, 192, 192, 0.8)',
'rgba(153, 102, 255, 0.8)',
'rgba(255, 159, 64, 0.8)',
'rgba(0, 200, 83, 0.8)',
'rgba(233, 30, 99, 0.8)',
'rgba(0, 188, 212, 0.8)',
'rgba(255, 87, 34, 0.8)',
'rgba(63, 81, 181, 0.8)',
'rgba(121, 85, 72, 0.8)',
'rgba(0, 150, 136, 0.8)',
];
$borderPalette = array_map(fn($c) => str_replace('0.8', '1', $c), $palette);

// Dataset Penjualan
$dataPenjualan = [];
foreach ($produk as $i => $p) {
$record = $penjualanPerBulan->firstWhere('nama_produk', $p);
$jumlah = $record ? (int)$record->total_terjual : 0;
$colorIndex = $i % count($palette);
$dataPenjualan[] = [
'label' => $p,
'data' => [$jumlah],
'backgroundColor' => $palette[$colorIndex],
'borderColor' => $borderPalette[$colorIndex],
'borderWidth' => 1,
];
}

// Dataset Stok
$dataStok = [];
foreach ($produkStok as $i => $p) {
$record = $stokTerbaruPerProduk->firstWhere('nama_produk', $p);
$jumlah = $record ? (int)$record->total_stok : 0;
$colorIndex = $i % count($palette);
$dataStok[] = [
'label' => $p,
'data' => [$jumlah],
'backgroundColor' => $palette[$colorIndex],
'borderColor' => $borderPalette[$colorIndex],
'borderWidth' => 1,
];
}
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const penjualanCtx = document.getElementById('penjualanChart').getContext('2d');
new Chart(penjualanCtx, {
    type: 'bar',
    data: {
        labels: ['{{ $latestPeriode }}'],
        datasets: @json($dataPenjualan)
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Terjual'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Penjualan Produk Bulan Terbaru',
                font: {
                    size: 18
                }
            }
        }
    }
});

const stokCtx = document.getElementById('stokChart').getContext('2d');
new Chart(stokCtx, {
    type: 'bar',
    data: {
        labels: ['Stok Saat Ini'],
        datasets: @json($dataStok)
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Stok'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Jumlah Stok Produk Saat Ini',
                font: {
                    size: 18
                }
            }
        }
    }
});
</script>
@endsection