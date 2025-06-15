@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">TAMBAH DATA TRANSAKSI</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <div class="row mx-2 my-2">

                        {{-- Tanggal --}}
                        <div class="table mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal"
                                class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal') }}">
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Barang --}}
                        <div class="table mb-3">
                            <label for="id_produk">Nama Barang</label>
                            <select class="form-control @error('id_produk') is-invalid @enderror" name="id_produk"
                                id="id_produk">
                                <option disabled selected value="">Pilih Produk</option>
                                @foreach ($produk as $item)
                                <option value="{{ $item->id_produk }}" data-harga="{{ $item->harga }}"
                                    {{ old('id_produk') == $item->id_produk ? 'selected' : '' }}>
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
                            <input type="number" name="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}"
                                placeholder="Masukkan jumlah">
                            @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Total --}}
                        <div class="table mb-3">
                            <label for="total">Total</label>
                            <p id="totalDisplay" class="form-control-static">Rp 0</p>
                            <input type="hidden" name="total" id="totalInput" value="{{ old('total', 0) }}">
                        </div>

                    </div>

                    <div class="table">
                        <button type="submit" class="btn btn-dark" name="save">Simpan</button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-dark">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk menghitung total
function calculateTotal() {
    var jumlah = document.getElementById('jumlah').value;
    var produkHarga = null;
    var totalDisplay = document.getElementById('totalDisplay');
    var totalInput = document.getElementById('totalInput');

    var selectedOption = document.querySelector("#id_produk option:checked");
    if (selectedOption) {
        produkHarga = selectedOption.getAttribute('data-harga');
    }

    if (!isNaN(jumlah) && jumlah != '' && produkHarga !== null) {
        var total = jumlah * produkHarga;
        totalDisplay.textContent = "Rp " + total.toLocaleString();
        totalInput.value = total;
    } else {
        totalDisplay.textContent = "Rp 0";
        totalInput.value = 0;
    }
}

// Event listener untuk input jumlah dan perubahan produk
document.getElementById('jumlah').addEventListener('input', calculateTotal);
document.getElementById('id_produk').addEventListener('change', calculateTotal);

// Panggil sekali saat load untuk inisialisasi total
window.onload = calculateTotal;
</script>
@endsection