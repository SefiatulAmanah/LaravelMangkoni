@extends('layouts.app')

@section('content')
<style>
/* Semua isi tabel rata kiri */
#datatables th,
#datatables td {
    text-align: left !important;
}

/* Header: teks rata kiri + ikon sorting tetap di kanan */
#datatables thead th {
    position: relative;
    padding-right: 20px;
    /* space for sorting icon */
    cursor: pointer;
}

#datatables thead th.sorting:after,
#datatables thead th.sorting_asc:after,
#datatables thead th.sorting_desc:after {
    position: absolute !important;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    float: none !important;
}
</style>

<div class="row mx-2 my-1">
    <h2 class="text-dark">DATA RETUR</h2>
</div>

<div class="row">
    <div class="col-md">
        <a href="{{ route('retur.create') }}" class="btn btn-dark btn-sm mb-3 mx-3">
            <i class="fa fa-users" aria-hidden="true"></i>&nbsp;Tambah Data
        </a>
        <div class="card shadow py-2 px-2">
            <div class="card-header my-1 mx-1">
                <table id="datatables" class="table table-striped table-bordered text-start" style="width:100%">
                    <thead class="table-dark text-start">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($retur as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ optional($item->produk)->nama_produk ?? '-' }}</td>
                            <td class="align-middle">{{ $item->jumlah }}</td>
                            <td class="align-middle">{{ $item->keterangan }}</td>
                            <td class="align-middle">
                                <a href="{{ route('retur.edit', $item->id_retur) }}"
                                    class="btn btn-sm btn-secondary me-1">
                                    Ubah
                                </a>
                                <form id="delete-form-{{ $item->id_retur }}"
                                    action="{{ route('retur.destroy', $item->id_retur) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $item->id_retur }}">
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

@if (Session::has('success'))
@push('scripts')
<script>
Swal.fire({
    title: 'Sukses!',
    text: '{{ Session::get("success") }}',
    icon: 'success',
    confirmButtonText: 'OK'
});
</script>
@endpush
@endif

@endsection

@push('scripts')
<!-- Pastikan jQuery, DataTables & SweetAlert2 sudah di-load di layout/app -->

<script>
$(document).ready(function() {
    $('#datatables').DataTable({
        responsive: true,
        order: [
            [0, 'asc']
        ], // default sorting kolom No ASC
        columnDefs: [{
            orderable: false,
            targets: 4 // kolom aksi tidak bisa sorting
        }]
    });

    $(document).on('click', '.delete-btn', function() {
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