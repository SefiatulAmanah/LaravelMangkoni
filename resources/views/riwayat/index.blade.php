@extends('layouts.app')

@section('content')
<div class="container mt-3" style="height: 600px; display: flex; flex-direction: column;">

    <h2>DATA RIWAYAT PENJUALAN</h2>

    {{-- Filter Bulan & Tahun + Tombol Filter dan Cetak --}}
    <form action="{{ route('riwayat.index') }}" method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-md-3">
            <label for="bulan" class="form-label mb-1">Pilih Bulan</label>
            <select name="bulan" id="bulan" class="form-select form-select-sm">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
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

        <div class="col-md-2">
            <a href="{{ route('riwayat.report', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}"
                class="btn btn-dark btn-sm w-100" style="width: 60px;" target=" _blank">
                <i class="bi bi-printer"></i> Cetak Data
            </a>
        </div>

    </form>

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

    <div class="card flex-grow-1 d-flex flex-column">
        <!-- Bungkus tabel dalam div scroll -->
        <div style="flex-grow: 1; overflow-y: auto;">
            <table id="riwayatTable" class="table table-bordered table-striped mb-0" style="width: 100%;">
                <thead class="table-dark text-start">
                    <tr>
                        <th class="text-start">No</th>
                        <th class="text-start">Tanggal</th>
                        <th class="text-start">Nama Produk</th>
                        <th class="text-start">Jumlah</th>
                        <th class="text-start">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                    <tr>
                        <td class="text-start align-middle">{{ $loop->iteration }}</td>
                        <td class="text-start align-middle">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</td>
                        <td class="text-start align-middle">
                            {{ $transaksi->produk->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                        <td class="text-start align-middle">{{ $transaksi->jumlah }}</td>
                        <td class="text-start align-middle"> Rp. {{ number_format($transaksi->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#riwayatTable').DataTable({
        responsive: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            }
        },
        scrollY: '400px',
        scrollCollapse: true,
        paging: true,
    });
});
</script>
@endpush