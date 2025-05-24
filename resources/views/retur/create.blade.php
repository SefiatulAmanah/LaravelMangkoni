@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">TAMBAH DATA RETUR</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('retur.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="id_produk">Nama Barang</label>
                        <select class="form-control @error('id_produk') is-invalid @enderror" name="id_produk"
                            id="id_produk">
                            <option disabled selected>Pilih Produk</option>
                            @foreach ($produk as $item)
                            <option value="{{ $item->id_produk }}" @if (old('id_produk')==$item->id_produk) selected
                                @endif>
                                {{ $item->nama_produk }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                            value="{{ old('jumlah') }}" placeholder="Masukkan jumlah">
                        @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" name="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            value="{{ old('keterangan') }}" placeholder="Masukkan keterangan">
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="table">
                        <button type="submit" class="btn btn-dark" name="save">Simpan</button>
                        <a href="{{ route('retur.index') }}" class="btn btn-dark">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection