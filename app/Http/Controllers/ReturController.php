<?php

namespace App\Http\Controllers;

use App\Models\retur;
use App\Models\produk;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatereturRequest;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            $produk = produk ::all();
            $retur = retur::orderBy('id_retur','ASC')->get();
            return view ('retur.index', compact('retur','produk'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Produk::orderBy('nama_produk', 'ASC')->get(); // Ambil semua produk
        return view('retur.create', compact('produk')); // kirim ke view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi data yang diterima
    $validatedData = $request->validate([
        'id_produk' => 'required|exists:produks,id_produk',  // Validasi produk ada
        'jumlah' => 'required|numeric',  // Validasi jumlah
        'keterangan' => 'required', // Validasi total
    ]);

    // Cari produk berdasarkan id_produk
    $produk = Produk::find($validatedData['id_produk']);
    if (!$produk) {
        return back()->with('error', 'Produk tidak ditemukan.');
    }

    // Simpan data retur
    $retur = retur::create([
        'id_produk' => $validatedData['id_produk'],
        'jumlah' => $validatedData['jumlah'],
        'keterangan' => $validatedData['keterangan'],  // Pastikan ini tidak kosong
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('retur.index')->with('success', 'Data Retur berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show(retur $retur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $retur)
    {
         // Ambil data produk dan retur berdasarkan id
        $produk = Produk::all(); // Ambil semua produk
        $retur = retur::find($retur); // Ambil data retur berdasarkan ID yang diberikan

        // Kirim data produk dan retur ke view
        return view('retur.edit', compact('retur', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, retur $retur)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'jumlah' => 'required|numeric',
            'keterangan' => 'required',
        ]);
    
        $retur->update($validated);
    
        return redirect()->route('retur.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($retur)
    {
         // Mencari data 'produksi' berdasarkan ID
        $retur = retur::findOrFail($retur);
        // Menghapus data 'retur'
        $retur->delete();
        // Mengalihkan kembali ke halaman index 'retur' dengan pesan sukses
        return redirect()->route('retur.index')->with('success', 'Data berhasil dihapus!');
    }
}