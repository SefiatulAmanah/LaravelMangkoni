@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">TAMBAH DATA PRODUK</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('produk.store') }}" method="POST">
                        @csrf
                        <div class="row mx-2 my-2">
                            <div class="table">
                                <label for="nama_produk">Nama Barang</label>
                                <input type="text" name="nama_produk" class="form-control" placeholder=""
                                    aria-label="First" value="{{ old('nama_produk') }}">
                                @error('nama_produk')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="table">
                                <label for="harga">Harga</label>
                                <input type="text" name="harga" class="form-control" placeholder="" aria-label="First"
                                    value="{{ old('harga') }}">
                                @error('harga')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="table">
                                <button type="submit" class="btn btn-dark" name="save">simpan</button>
                                <a href="{{ route('produk.index') }}" class="btn btn-dark">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection