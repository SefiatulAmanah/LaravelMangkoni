<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProdukImport;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        {
            $produk = produk::orderBy('id_produk','ASC')->get();
            return view ('produk.index', compact('produk'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Produk::all(); // ambil data produk untuk select dropdown
    return view('produk.create', compact('produk')); // kirim ke view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_produk' => 'required|string',
        'harga' => 'required|numeric',
    ]);

    Produk::create($validated);
    return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
}


    /**
     * Display the specified resource.
     */
    public function show(produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        // Debug: cek data yang diterima
        // dd($validated);

        // Update produk
        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Data Berhasil Diubah!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);


        Excel::import(new ProdukImport, $request->file('file'));

        return redirect()->route('produk.index')->with('success', 'Data berhasil diimport!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id_produk)
    {
        // Mencari produk berdasarkan id_produk
        $produk = Produk::findOrFail($id_produk);  // menggunakan id_produk langsung
        $produk->delete(); // Hapus produk
    
        return redirect()->route('produk.index')->with('deleted', 'Data berhasil dihapus.');
    }
    
}