<?php

namespace App\Http\Controllers;

use App\Models\produksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProduksiImport;
use Carbon\Carbon;


class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $produk = Produk::all();
    $query = Produksi::with('produk');

    // Filter bulan dan tahun jika dipilih
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('tanggal', $request->tahun);
    }

    $produksi = $query->orderBy('tanggal', 'desc')->get();

    // Ambil daftar tahun unik dari tabel produksi
    $tahunList = Produksi::selectRaw('YEAR(tanggal) as tahun')
        ->distinct()
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');

    return view('produksi.index', compact('produksi', 'tahunList'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Produk::orderBy('nama_produk', 'ASC')->get(); // Ambil semua produk
        return view('produksi.create', compact('produk')); // kirim ke view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $produk = Produk::find($request->id_produk);

        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required',
            'id_produk' => 'required',  // Menggunakan id_produk yang dipilih di form
            'jumlah' => 'required',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'id_produk.required' => 'Produk wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
        ]);

        Produksi::create([
            'tanggal' => $request->tanggal,
            'nama_barang' => $produk->nama_produk, // ini isi dari produk
            'jumlah' => $request->jumlah,
            'id_produk' => $request->id_produk
        ]);
        return redirect()->route('produksi.index')->with('success', 'Data berhasil disimpan!');
    }



    public function input(Request $request){

        // $validated = $request->validate([
        //     'hari'=> 'required',
        //     'tanggal'=> 'required',
        //     'nama_barang'=> 'required',
        //     'jumlah'=> 'required',
        // ]);
        // dd($validated);
        produksi::create([
            'tanggal'=> $request->input('tanggal'),
            'id_produk'=> $request->input('nama_barang'),
            'jumlah'=> $request->input('jumlah'),
        ]);
        return redirect()->route('produksi.index');
    }

public function report(Request $request)
{
    $query = Produksi::with('produk');

    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal', $request->bulan);
    }
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal', $request->tahun);
    }

    $produksi = $query->orderBy('tanggal', 'desc')->get();

    return view('produksi.report', compact('produksi'));
}

    /**
     * Display the specified resource.
     */
    public function show(produksi $produksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $produksi)
{
    // Ambil data produk dan produksi berdasarkan id
    $produk = Produk::all(); // Ambil semua produk
    $produksi = Produksi::find($produksi); // Ambil data produksi berdasarkan ID yang diberikan

    // Kirim data produk dan produksi ke view
    return view('produksi.edit', compact('produksi', 'produk'));
}


    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, produksi $produksi)
    // {
    //     $produksi = $produksi::find($produksi);
    //     // $produksi->update($request->all());

    //     // return redirect()->route('produksi$produksi.index')
    //     // dd($request);
    //     $rules = [
    //         'hari'=> 'required=',
    //         'tanggal'=> 'required',
    //         'nama_barang'=> 'required',
    //         'jumlah'=> 'required',
    //     ];
    //     // dd(request());
    //     // if ($request->nama_barang != $produksi->nama_barang) {
    //     //     $rules['nama_barang'] = 'required';
    //     // };
    //     $validated = $request->validate($rules);
    //     dd($validated);
    //     $produksi::find($produksi->id_produksi)->update($validated);
    //     return redirect('produksi')->with('success', 'Data Berhasil Diubah!');
    // }

    public function update(Request $request, Produksi $produksi)
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'id_produk' => 'required|exists:produks,id_produk',
        'jumlah' => 'required|numeric',
    ]);

    $produksi->update($validated);

    return redirect()->route('produksi.index')->with('success', 'Data berhasil diubah!');
}


public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx'
    ]);


    Excel::import(new ProduksiImport, $request->file('file'));

    return redirect()->route('produksi.index')->with('success', 'Data berhasil diimport!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($produksi)
{
    // Mencari data 'produksi' berdasarkan ID
    $produksi = Produksi::findOrFail($produksi);

    // Menghapus data 'produksi'
    $produksi->delete();

    // Mengalihkan kembali ke halaman index 'produksi' dengan pesan sukses
    return redirect()->route('produksi.index')->with('success', 'Data berhasil dihapus!');
}

}