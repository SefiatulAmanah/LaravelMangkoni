@extends('layouts.app')

@section('content')
<div class="row mx-1 my-1">
    <h2 class="text-dark">DATA STOK</h2>
</div>
<div class="row">
    <div class="col-md">
        <a href="{{ route('stok.report') }}" class="btn btn-dark btn-sm mb-2 mx-3">
            <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Cetak Data
        </a>
        <div class="card shadow py-2 px-2">
            <div class="card-header my-1 mx-1">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produks as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->stock_terbaru }}</td>
                            {{-- pastikan accessor stok terbaru ada di model Produk --}}
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection