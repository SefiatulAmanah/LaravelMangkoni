@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">UBAH DATA RIWAYAT</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('riwayat.update', $riwayat->id_riwayat) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row mx-2 my-2">
                            {{-- Bulan --}}
                            <div class="table">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="Pilih">Pilih</option>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                            </div>
                            <class="form-control @error('bulan') is-invalid @enderror"
                                value="{{ old('bulan', $riwayat->bulan) }}">
                                @error('bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>
                        {{-- Tanggal --}}
                        <div class="table mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal"
                                class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', $riwayat->tanggal) }}">
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Barang --}}
                        <div class="table mb-3">
                            <label for="id_produk">Nama Barang</label>
                            <select class="form-control @error('id_produk') is-invalid @enderror" name="id_produk"
                                id="id_produk">
                                <option disabled {{ old('id_produk', $riwayat->id_produk) ? '' : 'selected' }}>
                                    Pilih Produk</option>
                                @foreach ($produk as $item)
                                <option value="{{ $item->id_produk }}" data-harga="{{ $item->harga }}"
                                    {{ old('id_produk', $riwayat->id_produk) == $item->id_produk ? 'selected' : '' }}>
                                    {{ $item->nama_produk }} (Rp {{ number_format($item->harga, 2) }})
                                </option>
                                @endforeach
                            </select>
                            @error('id_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah --}}
                        <div class="table mb-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ old('jumlah', $riwayat->jumlah) }}" placeholder="Masukkan jumlah">
                            @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Total --}}
                        <div class="table">
                            <label for="total">Total</label>
                            <p id="totalDisplay" class="form-control-static">Rp
                                {{ number_format($riwayat->total, 2) }}</p>
                            <input type="hidden" name="total" id="totalInput"
                                value="{{ old('total', $riwayat->total) }}">
                        </div>
                        <div class="table">
                            <button type="submit" class="btn btn-dark" name="save">simpan</button>
                            <a href="{{ route('riwayat.index') }}" class="btn btn-dark">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk menghitung total
function calculateTotal() {
    var jumlah = document.getElementById('jumlah').value; // Mengambil jumlah
    var idProduk = document.getElementById('id_produk').value; // Mengambil ID produk yang dipilih
    var totalDisplay = document.getElementById('totalDisplay'); // Menampilkan total
    var totalInput = document.getElementById('totalInput'); // Menyimpan total yang dihitung

    // Mengambil harga produk dari data atribut
    var produkHarga = document.querySelector("#id_produk option:checked").getAttribute('data-harga');

    if (!isNaN(jumlah) && jumlah != '') {
        var total = jumlah * produkHarga; // Menghitung total berdasarkan jumlah dan harga produk
        totalDisplay.textContent = "Rp " + total.toLocaleString(); // Menampilkan total dalam format Rupiah
        totalInput.value = total; // Menyimpan total di input hidden
    } else {
        totalDisplay.textContent = "Rp 0"; // Jika jumlah tidak valid, tampilkan Rp 0
        totalInput.value = 0; // Set total menjadi 0
    }
}

// Memanggil fungsi calculateTotal ketika input jumlah atau produk berubah
document.getElementById('jumlah').addEventListener('input', calculateTotal); // Ketika jumlah diubah
document.getElementById('id_produk').addEventListener('change', calculateTotal); // Ketika produk diubah
</script>
@endsection