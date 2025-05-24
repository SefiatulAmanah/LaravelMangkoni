<?php

namespace App\Http\Controllers;

use App\Models\tb_transaksi;
use App\Models\produk;
use App\Http\Requests\Storetb_transaksiRequest;
use App\Http\Requests\Updatetb_transaksiRequest;
use Illuminate\Http\Request;

class TbTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $produk = Produk::all();
    $tb_transaksi = tb_transaksi::with('produk')->orderBy('id_transaksi', 'DESC')->get(); // <<< tambah with('produk')

    return view('transaksi.index', compact('tb_transaksi', 'produk'));
    
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = produk::orderBy('nama_produk', 'ASC')->get();
        return view('transaksi.create', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi data yang diterima
    $validatedData = $request->validate([
        'tanggal' => 'required',
        'id_produk' => 'required|exists:produks,id_produk',  // Validasi produk ada
        'jumlah' => 'required|numeric|min:1',  // Validasi jumlah
        'total' => 'required|numeric|min:0', // Validasi total
    ]);

    // Cari produk berdasarkan id_produk
    $produk = Produk::find($validatedData['id_produk']);
    if (!$produk) {
        return back()->with('error', 'Produk tidak ditemukan.');
    }

    // Hitung total transaksi
    $total = $validatedData['jumlah'] * $produk->harga; // Perhitungan total

    // Simpan transaksi
    tb_transaksi::create([
        'tanggal' => $request->tanggal,
        'id_produk' => $request->id_produk,
        'jumlah' => $request->jumlah,
        'total' => $total,  // Simpan total yang sudah dihitung
    ]);

    return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
}

        

    /**
     * Display the specified resource.
     */
    public function show(tb_transaksi $tb_transaksi)
    {
        // Implementasi lainnya
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $tb_transaksi)
    {
        $produk = Produk::all(); // Ambil semua produk
        $tb_transaksi = tb_transaksi::find($tb_transaksi); // Ambil data tb_transaksi berdasarkan ID yang diberikan
    
        // Kirim data produk dan tb_transaksi ke view
        return view('transaksi.edit', compact('tb_transaksi', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'id_produk' => 'required|exists:produks,id_produk',
        'jumlah' => 'required|numeric|min:1',
        'total' => 'required|numeric|min:0',
    ]);

    $tb_transaksi = tb_transaksi::findOrFail($id); // <-- Temukan dulu berdasarkan ID
    $produk = Produk::find($validated['id_produk']);
    $total = $validated['jumlah'] * $produk->harga;

    $tb_transaksi->update([
        'tanggal' => $validated['tanggal'],
        'id_produk' => $validated['id_produk'],
        'jumlah' => $validated['jumlah'],
        'total' => $total,
    ]);

    return redirect()->route('transaksi.index')->with('success', 'Data berhasil diubah!');
}

public function report()
    {
        $tb_transaksi = tb_transaksi::get();
        return view ('transaksi.report', compact('tb_transaksi'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tb_transaksi)
    {
        // Mencari data '$tb_transaksi' berdasarkan ID
        $tb_transaksi = tb_transaksi::findOrFail($tb_transaksi);
        // Menghapus data 'tb_transaksi'
        $tb_transaksi->delete();
        
        // Mengalihkan kembali ke halaman index 'tb_transaksi' dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Data berhasil dihapus!');
    }
}