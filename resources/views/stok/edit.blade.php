@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">UBAH DATA stok</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('stok.update', $stok->id_stok) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row mx-2 my-2">
                            {{-- Nama Barang --}}
                            <div class="table mb-3">
                                <label for="id_produk">Nama Barang</label>
                                <select class="form-control @error('id_produk') is-invalid @enderror" name="id_produk"
                                    id="id_produk">
                                    <option disabled {{ old('id_produk', $stok->id_produk) ? '' : 'selected' }}>
                                        Pilih Produk</option>
                                    @foreach ($produk as $item)
                                    <option value="{{ $item->id_produk }}"
                                        {{ old('id_produk', $stok->id_produk) == $item->id_produk ? 'selected' : '' }}>
                                        {{ $item->nama_produk }}
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
                                    class="form-control @error('jumlah') is-invalid @enderror"
                                    value="{{ old('jumlah', $stok->jumlah) }}" placeholder="Masukkan jumlah">
                                @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <button type="submit" class="btn btn-dark">Simpan</button>
                        <a href="{{ route('stok.index') }}" class="btn btn-dark">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection