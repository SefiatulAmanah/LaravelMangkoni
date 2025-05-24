@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">TAMBAH DATA STOK</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('stok.store') }}" method="POST">
                        @csrf
                        <div class="row mx-2 my-2">
                            <div class="table">
                                <label for="id_produk">Nama Barang</label>
                                <select class="form-control" name="id_produk" id="id_produk">
                                    <option disabled selected>Pilih Produk</option>
                                    @foreach ($produk as $item)
                                    <option value="{{ $item->id_produk }}" @if (old('id_produk')==$item->id_produk)
                                        selected @endif>
                                        {{ $item->nama_produk }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <br>
                            <div class="table">
                                <label for="jumlah">Jumlah</label>
                                <input type="text" name="jumlah" class="form-control" placeholder="" aria-label="First">
                            </div>
                            <div class="table">
                                <button type="submit" class="btn btn-dark" name="save">simpan</button>
                                <a href="{{ route('stok.index') }}" class="btn btn-dark">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection