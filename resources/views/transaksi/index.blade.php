@extends('layouts.app')

@section('content')
<div class="row mx-2 my-1">
    <h2 class="text-dark">DATA TRANSAKSI</h2>
</div>

{{-- SweetAlert success --}}
@if (Session::has('success'))
<script>
Swal.fire({
    title: 'Sukses!',
    text: '{{ Session::get("success") }}',
    icon: 'success',
    confirmButtonText: 'OK'
});
</script>
@endif

{{-- Filter Bulan & Tahun --}}
<form action="{{ route('transaksi.index') }}" method="GET" class="row g-2 align-items-end mx-3 mb-3">
    <div class="col-md-3">
        <label for="bulan" class="form-label mb-1">Pilih Bulan</label>
        <select name="bulan" id="bulan" class="form-select form-select-sm">
            <option value="">Semua Bulan</option>
            @foreach(range(1, 12) as $m)
            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="tahun" class="form-label mb-1">Pilih Tahun</label>
        <select name="tahun" id="tahun" class="form-select form-select-sm">
            <option value="">Semua Tahun</option>
            @foreach($tahunList as $tahun)
            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                {{ $tahun }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-dark btn-sm w-100 mt-3">
            <i class="fa fa-filter"></i> Filter
        </button>
    </div>
</form>

<div class="row">
    <div class="col-md">
        <div class="mx-4 mb-3 d-flex gap-2">
            <a href="{{ route('transaksi.create') }}" class="btn btn-dark btn-sm">
                <i class="bi bi-plus"></i>&nbsp;Tambah Data
            </a>
            <a href="{{ route('transaksi.report', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}"
                class="btn btn-dark btn-sm">
                <i class="bi bi-printer"></i>&nbsp;Cetak Data
            </a>
            <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload"></i>&nbsp;Import Data
            </button>
        </div>
        <div class="card shadow py-2 px-2 mx-3">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table id="example" class="table table-striped table-bordered table-sm w-100">
                        <thead class="table-dark text-center" style="position: sticky; top: 0; z-index: 1020;">
                            <tr>
                                <th class="text-start">No</th>
                                <th class="text-start">Tanggal</th>
                                <th class="text-start">Nama Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Total</th>
                                <th class="text-start">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tb_transaksi as $item)
                            <tr>
                                <td class="text-start align-middle">{{ $loop->iteration }}</td>
                                <td class="text-start align-middle">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('m-d-Y') }}</td>
                                <td class="text-start align-middle">{{ optional($item->produk)->nama_produk ?? '-' }}
                                </td>
                                <td class="text-center align-middle">{{ $item->jumlah }}</td>
                                <td class="text-end align-middle">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td class="text-start align-middle">
                                    <a href="{{ route('transaksi.edit', $item->id_transaksi) }}"
                                        class="btn btn-sm btn-secondary me-1">
                                        Ubah
                                    </a>
                                    <form id="delete-form-{{ $item->id_transaksi }}"
                                        action="{{ route('transaksi.destroy', $item->id_transaksi) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                            data-id="{{ $item->id_transaksi }}">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('transaksi.import') }}" method="POST" enctype="multipart/form-data" class="w-100">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="importModalLabel">Import Data Transaksi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih file Excel (.xlsx)</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx" required>
                        <small class="text-muted">Pastikan file dalam format .xlsx</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#example').DataTable({
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            }
        }
    });

    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endpush

@endsection