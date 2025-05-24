@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Halaman Peramalan</h1>

    <form method="GET" action="{{ route('peramalan.index') }}">
        <div class="row">
            <div class="col-md-2">
                <label>Tipe Peramalan</label>
                <select name="tipe" class="form-control" id="tipe">
                    <option value="minggu" {{ ($tipe ?? '') == 'minggu' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulan" {{ ($tipe ?? '') == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Nama Barang</label>
                <select name="id_produk" class="form-control">
                    <option disabled selected>Pilih Produk</option>
                    @foreach($produkList as $produk)
                    <option value="{{ $produk->id }}"
                        {{ (isset($id_produk) && $id_produk == $produk->id) ? 'selected' : '' }}>
                        {{ $produk->nama_produk }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Tahun</label>
                <input type="number" name="tahun" value="{{ $tahun ?? date('Y') }}" class="form-control">
            </div>
            <div class="col-md-2" id="periode-container" style="display: none;">
                <label id="label-minggu-bulan">Minggu ke-</label>
                <input type="number" name="periode" value="{{ $periode ?? '' }}" class="form-control" id="periode-input"
                    min="1">
            </div>
            <div class="col-md-2">
                <label>Alpha</label>
                <input type="text" name="alpha" value="{{ $alpha ?? 0.5 }}" class="form-control">
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label><br>
                <button type="submit" class="btn btn-dark">Filter</button>
            </div>
        </div>
    </form>

    <hr>

    @if(isset($transaksi) && count($transaksi) > 0)
    <h2>Data Transaksi ({{ ucfirst($tipe) }} ke-{{ $periode }}, Tahun {{ $tahun }})</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $item)
            <tr>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h2>Hasil SMA</h2>
    <pre>{{ print_r($hasilSMA, true) }}</pre>

    <h2>Hasil DES</h2>
    <pre>{{ print_r($hasilDES, true) }}</pre>

    @else
    <div class="alert alert-info mt-3">
        Belum ada data transaksi untuk filter yang dipilih.
    </div>
    @endif
</div>

<script>
// JavaScript untuk mengubah label input "Minggu ke- / Bulan ke-" dan menampilkan input periode
const tipeSelect = document.querySelector('select[name="tipe"]');
const label = document.getElementById('label-minggu-bulan');
const periodeContainer = document.getElementById('periode-container');
const periodeInput = document.getElementById('periode-input');

function updatePeriodeField() {
    if (tipeSelect.value === 'minggu') {
        label.innerText = 'Minggu ke-';
        periodeInput.min = 1;
        periodeInput.max = 53;
        periodeContainer.style.display = 'block';
    } else if (tipeSelect.value === 'bulan') {
        label.innerText = 'Bulan ke-';
        periodeInput.min = 1;
        periodeInput.max = 12;
        periodeContainer.style.display = 'block';
    } else {
        periodeContainer.style.display = 'none';
    }
}

// Inisialisasi field saat halaman pertama kali dibuka
updatePeriodeField();

// Perbarui saat tipe peramalan dipilih
tipeSelect.addEventListener('change', updatePeriodeField);
</script>

@endsection