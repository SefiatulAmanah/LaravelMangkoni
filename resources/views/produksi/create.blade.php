@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">TAMBAH DATA PRODUKSI</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('produksi.store') }}" method="POST">
                        @csrf
                        <div class="row mx-2 my-2">
                            <div class="table">
                                <label for="hari">Hari</label>
                                <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror">
                                    <option value="">Pilih</option>
                                    <option value="senin" {{ old('hari') == 'senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="selasa" {{ old('hari') == 'selasa' ? 'selected' : '' }}>Selasa
                                    </option>
                                    <option value="rabu" {{ old('hari') == 'rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="kamis" {{ old('hari') == 'kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="jumat" {{ old('hari') == 'jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="sabtu" {{ old('hari') == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="minggu" {{ old('hari') == 'minggu' ? 'selected' : '' }}>Minggu
                                    </option>
                                </select>
                                @error('hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="table">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="table">
                                <label for="id_produk">Nama Barang</label>
                                <select class="form-control @error('id_produk') is-invalid @enderror" name="id_produk"
                                    id="id_produk">
                                    <option disabled {{ !old('id_produk') ? 'selected' : '' }}>Pilih Produk</option>
                                    @foreach ($produk as $item)
                                    <option value="{{ $item->id_produk }}"
                                        {{ old('id_produk') == $item->id_produk ? 'selected' : '' }}>
                                        {{ $item->nama_produk }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="table">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" name="jumlah"
                                    class="form-control @error('jumlah') is-invalid @enderror"
                                    value="{{ old('jumlah') }}">
                                @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="table mt-3">
                                <button type="submit" class="btn btn-dark" name="save">Simpan</button>
                                <a href="{{ route('produksi.index') }}" class="btn btn-dark">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection