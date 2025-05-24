<?php

namespace App\Http\Controllers;

use App\Models\stok;
use App\Models\produk;
use App\Models\produksi;
use App\Models\tb_transaksi;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua produk beserta relasi produksis dan tb_transaksis
        $produks = Produk::with(['produksis', 'tb_transaksis'])->get();

        foreach ($produks as $produk) {
            $stokAkhir = $produk->stock_terbaru;

            Stok::updateOrCreate(
                ['id_produk' => $produk->id_produk], // kunci pencarian
                ['jumlah' => $stokAkhir]              // update stok
            );
        }

        return view('stok.index', compact('produks'));
    }


public function report()
{
    $stok = stok::get();
    return view ('stok.report', compact('stok'));
}
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(stok $stok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $stok)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stok $stok)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($stok)
    {
        
    }
}