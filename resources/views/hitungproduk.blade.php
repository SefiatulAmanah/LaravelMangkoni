@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-dark mb-4">Hitung Penjualan Produk</h2>

    <form method="GET" action="{{ route('hitung.produk') }}" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="id_produk" class="form-label">Pilih Produk:</label>
                <select name="id_produk" id="id_produk" class="form-select" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($produkList as $produk)
                    <option value="{{ $produk->id_produk }}"
                        {{ $selectedProduk == $produk->id_produk ? 'selected' : '' }}>
                        {{ $produk->nama_produk }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="tahun" class="form-label">Pilih Tahun:</label>
                <select name="tahun" id="tahun" class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    @foreach ($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ $selectedTahun == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-dark">Hitung</button>
            </div>
        </div>
    </form>

    @if (!empty($data))
    <div class="mb-3">
        <a href="{{ route('cetak.laporan', ['id_produk' => $selectedProduk, 'tahun' => $selectedTahun]) }}"
            class="btn btn-outline-primary" target="_blank">
            Cetak Laporan
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5>Penjualan Per Minggu</h5>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Periode</th>
                        <th>Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perMinggu'] as $minggu => $jumlah)
                    <tr>
                        <td>Periode {{ $loop->iteration }} ({{ $minggu }})</td>
                        <td>{{ $jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h5>Penjualan Per Bulan</h5>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Periode</th>
                        <th>Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perBulan'] as $bulan => $jumlah)
                    <tr>
                        <td>Periode {{ $loop->iteration }} ({{ \Carbon\Carbon::parse($bulan . '-01')->format('F Y') }})
                        </td>
                        <td>{{ $jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <h5>Rekapitulasi Jumlah Produk Terjual</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Terjual</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $produkList->where('id_produk', $selectedProduk)->first()->nama_produk ?? '-' }}</td>
                    <td>{{ array_sum($data['perMinggu']->toArray()) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection