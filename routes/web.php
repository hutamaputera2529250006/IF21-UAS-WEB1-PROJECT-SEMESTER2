<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::resource('kategori',KategoriController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/laporan',[LaporanController::class,'index'])->name('laporan.index');
    Route::get('/laporan/export',[LaporanController::class,'export'])->name('laporan.export');
});
