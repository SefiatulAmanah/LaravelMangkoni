<?php

namespace App\Http\Controllers;

use App\Models\riwayat;
use App\Models\produk;
use App\Models\tb_transaksi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreriwayatRequest;
use App\Http\Requests\UpdateriwayatRequest;

class RiwayatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil data transaksi beserta relasi produk (relasi sudah ada di model tb_transaksi)
        $transaksis = tb_transaksi ::with('produk')->orderBy('tanggal', 'desc')->get();

        // Ambil daftar tahun unik dari kolom tanggal transaksi
        $tahunList = tb_transaksi::selectRaw('YEAR(tanggal) as tahun')
                        ->distinct()
                        ->orderBy('tahun', 'desc')
                        ->pluck('tahun');

        // Query data riwayat transaksi, dengan filter bulan & tahun jika ada
        $query = tb_transaksi::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $transaksis = $query->with('produk')->get();

        return view('riwayat.index', compact('transaksis', 'tahunList'));
    }
public function report(Request $request)
{
    $query = tb_transaksi::with('produk');

    if ($request->bulan) {
        $query->whereMonth('tanggal', $request->bulan);
    }

    if ($request->tahun) {
        $query->whereYear('tanggal', $request->tahun);
    }

    $transaksis = $query->get();

    return view('riwayat.report', compact('transaksis'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Produk::orderBy('nama_produk', 'ASC')->get(); // Ambil semua produk
        return view('riwayat.create', compact('produk')); // kirim ke view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
    $validatedData = $request->validate([
        'bulan' => 'required',
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

    // Hitung total riwayat
    $total = $validatedData['jumlah'] * $produk->harga; // Perhitungan total

    // Simpan riwayat
    riwayat::create([
        'bulan' => $request->bulan,
        'tanggal' => $request->tanggal,
        'id_produk' => $request->id_produk,
        'jumlah' => $request->jumlah,
        'total' => $total,  // Simpan total yang sudah dihitung
    ]);

    return redirect()->route('riwayat.index')->with('success', 'Riwayat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(riwayat $riwayat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $riwayat)
    {
        $produk = Produk::all(); // Ambil semua produk
        $riwayat = riwayat::find($riwayat); // Ambil data riwayat berdasarkan ID yang diberikan
    
        // Kirim data produk dan riwayat ke view
        return view('riwayat.edit', compact('riwayat', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, riwayat $riwayat)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'bulan' => 'required',
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
    
        // Hitung total riwayat
        $total = $validatedData['jumlah'] * $produk->harga; // Perhitungan total
    
        // Update riwayat
        $riwayat->update([
            'bulan' => $request->bulan,
            'tanggal' => $request->tanggal,
            'id_produk' => $request->id_produk,
            'jumlah' => $request->jumlah,
            'total' => $total,  // Simpan total yang sudah dihitung
        ]);
    
        return redirect()->route('riwayat.index')->with('success', 'Riwayat berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($riwayat)
    {
          // Mencari data '$riwayat' berdasarkan ID
          $riwayat = riwayat::findOrFail($riwayat);
          // Menghapus data 'riwayat'
          $riwayat->delete();
          
          // Mengalihkan kembali ke halaman index 'riwayat' dengan pesan sukses
          return redirect()->route('riwayat.index')->with('success', 'Data berhasil dihapus!');
    }
}