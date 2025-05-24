@extends('layouts.app')

@section('content')
<div class="my-3 px-2">
    <div class="row">
        <div class="col-md-12">

            <h4 class="text-dark mb-3">DATA PRODUK</h4>

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
            @if (Session::has('deleted'))
            <script>
            Swal.fire({
                title: 'Dihapus!',
                text: '{{ Session::get("deleted") }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            </script>
            @endif
            <div class="mb-3">
                <a href="{{ route('produk.create') }}" class="btn btn-dark btn-sm">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            </div>

            <div class="card shadow-sm rounded">
                <div class="card-body px-2 py-2">
                    <div class="table-responsive">
                        <table id="datatables" class="table table-bordered table-striped table-sm w-100">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th class="text-start ">No</th>
                                    <th class="text-start">Nama Barang</th>
                                    <th class="text-start">Harga</th>
                                    <th class="text-start">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produk as $item)
                                <tr>
                                    <td class="text-start">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $item->nama_produk }}</td>
                                    <td class="text-start">{{ $item->harga }}</td>
                                    <td class="text-start">
                                        <a href="{{ route('produk.edit', $item->id_produk) }}"
                                            class="btn btn-sm btn-secondary me-1">
                                            Edit
                                        </a>
                                        <form id="delete-form-{{ $item->id_produk }}"
                                            action="{{ route('produk.destroy', $item->id_produk) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $item->id_produk }}">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data Produk Tidak Ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#datatables').DataTable({
        responsive: true
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

@endsection