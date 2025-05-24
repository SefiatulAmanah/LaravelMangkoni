<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TbTransaksiController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Models\transaksi;
use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk dashboard setelah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Sesuaikan dengan view dashboard kamu
    })->name('dashboard');
});

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/home', function () {
    return view('layouts.home');
});
// Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
// Route::post('/login', [LoginController::class, 'authenticate']);
// Route::post('/logout', [LoginController::class, 'logout']);

// Route::get('/register', [RegisterController::class, 'index'])->middleware('guest');
// Route::post('/register', [RegisterController::class, 'store']);

Route::resource('produksi', ProduksiController::class);
Route::resource('stok', StokController::class);
Route::resource('riwayat', RiwayatController::class);
Route::resource('retur', ReturController::class);
Route::resource('transaksi', TbTransaksiController::class);
Route::resource('produk', ProdukController::class);
Route::resource('peramalan', PeramalanController::class);
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);


Route::get('report-stok', [StokController::class,'report'])->name('stok.report');
Route::get('report-produksi', [ProduksiController::class,'report'])->name('produksi.report');
Route::get('report-tb_transaksi', [TbTransaksiController::class,'report'])->name('transaksi.report');
Route::match(['get', 'post'], '/import-produksi', [ProduksiController::class, 'import'])->name('produksi.import');