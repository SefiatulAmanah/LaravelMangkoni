@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">UBAH DATA PRODUK</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row mx-2 my-2">

                            {{-- Nama Barang --}}
                            <div class="table mb-3">
                                <label for="nama_produk">Nama Barang</label>
                                <input type="text" name="nama_produk"
                                    class="form-control @error('nama_produk') is-invalid @enderror"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}"
                                    placeholder="Masukkan nama barang">
                                @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Harga --}}
                            <div class="table mb-3">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga"
                                    class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga', $produk->harga) }}" placeholder="Masukkan harga">
                                @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <button type="submit" class="btn btn-dark">Simpan</button>
                        <a href="{{ route('produk.index') }}" class="btn btn-dark">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection