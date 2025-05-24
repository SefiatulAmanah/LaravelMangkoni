@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">TAMBAH DATA TRANSAKSI</h6>
            </div>
            <div class="card-body">

                <!-- {{-- Tampilkan semua pesan error --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif -->

                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <div class="row mx-2 my-2">
                        <div class="table mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal"
                                class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal') }}">
                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="table mb-3">
                            <label for="id_produk">Nama Barang</label>
                            <select class="form-control @error('id_produk') is-invalid @enderror" name="id_produk"
                                id="id_produk" onchange="calculateTotal()">
                                <option disabled selected>Pilih Produk</option>
                                @foreach ($produk as $item)
                                <option value="{{ $item->id_produk }}" data-harga="{{ $item->harga }}"
                                    @if(old('id_produk')==$item->id_produk) selected @endif>
                                    {{ $item->nama_produk }} (Rp {{ number_format($item->harga, 2) }})
                                </option>
                                @endforeach
                            </select>
                            @error('id_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="table mb-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}"
                                placeholder="Masukkan Jumlah" onkeyup="calculateTotal()">
                            @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="table mb-3">
                            <label for="total">Total</label>
                            <p id="totalDisplay" class="form-control-static">Rp 0</p>
                            <input type="hidden" name="total" id="totalInput" value="0">
                        </div>

                        <div class="table mb-3">
                            <button type="submit" class="btn btn-dark" name="save">Simpan</button>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-dark">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function calculateTotal() {
    var jumlah = document.getElementById('jumlah').value;
    var selectedOption = document.querySelector("#id_produk option:checked");
    var harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;

    var totalDisplay = document.getElementById('totalDisplay');
    var totalInput = document.getElementById('totalInput');

    if (!isNaN(jumlah) && jumlah !== '' && harga) {
        var total = jumlah * parseFloat(harga);
        totalDisplay.textContent = "Rp " + total.toLocaleString('id-ID');
        totalInput.value = total;
    } else {
        totalDisplay.textContent = "Rp 0";
        totalInput.value = 0;
    }
}
</script>
@endsection