<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $totalKategori = Kategori::count();
        $totalProduk = Produk::count();
        $totalKaryawan = Karyawan::count();
        $totalTransaksi = Transaksi::count();

        $transaksibulanIni = Transaksi::whereMonth('tanggal_transaksi',now()->month)->whereYear('tanggal_transaksi',now()->year);
        $omsetBulanIni = (clone $transaksibulanIni)->sum('total_harga');
        $modalBulanIni = (clone $transaksibulanIni)->sum('total_modal');
        $profitBulanIni = $omsetBulanIni - $modalBulanIni;

        $transaksiTerbaru = Transaksi::with('karyawan')
            ->orderByDesc('tanggal_transaksi')
            ->limit(5)
            ->get();

            return view('dashboard.index',compact(
                'totalKategori','totalProduk','totalKaryawan','totalTransaksi','omsetBulanIni','modalBulanIni',
                'profitBulanIni','transaksiTerbaru'
            ));
    }
}
