@extends('layouts.app')

@section('content')
<div class="row mx-2 my-1">
    <h2 class="text-dark">DATA TRANSAKSI</h2>
</div>

{{-- alert --}}
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

<div class="row">
    <div class="col-md">
        <a href="{{ route('transaksi.create') }}" class="btn btn-dark btn-sm mb-2 mx-3">
            <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Tambah Data
        </a>
        <a href="{{ route('transaksi.report') }}" class="btn btn-dark btn-sm mb-2">
            <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Cetak Data
        </a>

        <div class="card shadow py-2 px-2 mx-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered table-sm w-100">
                        <thead class="table-dark text-start">
                            <tr>
                                <th class="text-start">No</th>
                                <th class="text-start">Tanggal</th>
                                <th class="text-start">Nama Produk</th>
                                <th class="text-start">Jumlah</th>
                                <th class="text-start">Total</th>
                                <th class="text-start">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tb_transaksi as $item)
                            <tr>
                                <td class="text-start align-middle">{{ $loop->iteration }}</td>
                                <td class="text-start align-middle">{{ $item->tanggal }}</td>
                                <td class="text-start align-middle">{{ optional($item->produk)->nama_produk ?? '-' }}
                                </td>
                                <td class="text-start align-middle">{{ $item->jumlah }}</td>
                                <td class="text-end align-middle">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td class="text-start align-middle">
                                    <a href="{{ route('transaksi.edit', $item->id_transaksi) }}"
                                        class="btn btn-sm btn-secondary me-1">
                                        Ubah
                                    </a>
                                    <form id="delete-form-{{ $item->id_transaksi }}"
                                        action="{{ route('transaksi.destroy', $item->id_transaksi) }}" method="POST"
                                        style="display: inline;">
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

{{-- Script SweetAlert & DataTables --}}
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