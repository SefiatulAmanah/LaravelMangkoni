@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">UBAH DATA PRODUKSI</h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="{{ route('produksi.update', $produksi->id_produksi) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row mx-2 my-2">
                            {{-- Tanggal --}}
                            <div class="table mb-3">
                                <label for="tanggal">Tanggal</label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', $produksi->tanggal) }}">
                                @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nama Barang --}}
                            <div class="table mb-3">
                                <label for="id_produk">Nama Barang</label>
                                <select class="form-control select2 @error('id_produk') is-invalid @enderror"
                                    name="id_produk" id="id_produk">
                                    <option disabled {{ old('id_produk', $produksi->id_produk) ? '' : 'selected' }}>
                                        Pilih Produk</option>
                                    @foreach ($produk as $item)
                                    <option value="{{ $item->id_produk }}"
                                        {{ old('id_produk', $produksi->id_produk) == $item->id_produk ? 'selected' : '' }}>
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
                                    value="{{ old('jumlah', $produksi->jumlah) }}" placeholder="Masukkan jumlah">
                                @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <button type="submit" class="btn btn-dark">Simpan</button>
                        <a href="{{ route('produksi.index') }}" class="btn btn-dark">Kembali</a>
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
    padding-left: 12px;
    /* samakan padding kiri dengan input biasa */
    padding-right: 12px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    font-size: 1rem;
    color: #495057;
    text-align: left;
    /* pastikan teks rata kiri */
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
    padding-left: 0;
    /* hilangkan padding ekstra di dalam teks */
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
        minimumResultsForSearch: 0, // selalu tampilkan kotak search
        dropdownAutoWidth: true,
    }).on('select2:open', function() {
        $('.select2-search__field').focus();
    });
});
</script>
@endpush