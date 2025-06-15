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
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal') }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="table">
                                <label for="id_produk">Nama Barang</label>
                                <select class="form-control select2 @error('id_produk') is-invalid @enderror"
                                    name="id_produk" id="id_produk">
                                    <option value="" disabled {{ !old('id_produk') ? 'selected' : '' }}>Pilih Produk
                                    </option>
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

@push('scripts')
<style>
/* Dropdown select2 agar pas dengan input */
.select2-container--default .select2-selection--single {
    height: 38px;
    padding: 6px 12px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    font-size: 1rem;
    color: #495057;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 10px;
}
</style>
<script>
$(document).ready(function() {
    $('#id_produk').select2({
        placeholder: "Pilih Produk",
        allowClear: true,
        width: '100%',
        // Membuat input search langsung aktif tanpa harus klik dropdown dulu
        minimumResultsForSearch: 0, // selalu tampilkan kotak search
        // Membuat dropdown terbuka otomatis ketika input fokus
        dropdownAutoWidth: true,
        // Agar ketika mengetik langsung muncul hasil pencarian
        // (ini default sudah aktif, tapi kita pastikan)
        // Bisa ditambahkan ajax jika datanya sangat besar
    }).on('select2:open', function() {
        $('.select2-search__field').focus();
    });
});
</script>
@endpush