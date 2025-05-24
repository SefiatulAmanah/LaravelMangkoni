@extends('layouts.app')

@section('content')
<div class="row mx-3 my-1">
    <h2 class="text-dark">DATA RIWAYAT</h2>
</div>
{{-- alert --}}
@if (Session::has('success'))
<script>
Swal.fire({
    title: 'Sukses!',
    text: '{{ Session::get('
    success ') }}',
    icon: 'success',
    confirmButtonText: 'OK'
});
</script>
@endif

<script>
function closeAlert() {
    document.getElementById('successAlert').style.display = 'none';
}
</script>

<div class="row">
    <div class="col-md">
        <a href="{{ route('riwayat.create') }}" class="btn btn-dark btn-sm mb-2 mx-3"><i class="fa fa-users"
                aria-hidden="true"></i>&nbsp;Tambah Data</a>
        <div class="card shadow py-2 px-2">
            <div class="card-header my-1 mx-1">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Bulan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Total</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayat as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->index + 1 }}</td>
                            <td class="align-middle">{{ $item->bulan }}</td>
                            <td class="align-middle">{{ $item->tanggal }}</td>
                            <td class="align-middle">{{ optional($item->produk)->nama_produk ?? '-' }}</td>
                            <td class="align-middle">{{ $item->jumlah }}</td>
                            <td class="align-middle">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="align-middle">
                                <a href="{{ route('riwayat.edit', $item->id_riwayat) }}" class="btn btn-sm"
                                    style="background-color: #6c757d; color: white; margin-right: 5px;">
                                    Ubah
                                </a>
                                <form id="delete-form-{{ $item->id_riwayat }}"
                                    action="{{ route('riwayat.destroy', $item->id_riwayat) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-sm"
                                        style="background-color: #6c757d; color: white;"
                                        id="delete-btn-{{ $item->id_riwayat }}">
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

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Menambahkan event listener pada tombol Delete untuk setiap produk
@foreach($produk as $item)
document.getElementById('delete-btn-{{ $item->id_produk }}').addEventListener('click', function() {
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
            // Jika tombol Hapus diklik, kirimkan form
            document.getElementById('delete-form-{{ $item->id_produk }}').submit();
        }
    });
});
@endforeach
</script>
@endsection