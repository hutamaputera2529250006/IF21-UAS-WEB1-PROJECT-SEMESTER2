<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request){
        $periode = $request->input('periode','monthly');
        $tahun = $request->input('tahun', now()->year);
        $bulan = $request->input('bulan', now()->month);

        $query = Transaksi::query();

        switch($periode){
            case 'daily':
                $query->whereDate('tanggal_transaksi', today());
                $title = 'Laporan Harian' . now()->translatedFormat('d F Y');
                break;

            case 'weekly':
                $query->whereBetween('tanggal_transaksi', [
                    now()->startOfWeek(), now()->endOfWeek()
                ]);
                $title = 'Laporan Mingguan' . now()->weekOfYear . ', ' . $tahun;
                break;

            case 'yearly':
                $query->whereYear('tanggal_transaksi', $tahun);
                $title = 'Laporan Tahunan' . $tahun;
                break;

            default: // monthly
                $query->whereYear('tanggal_transaksi', $tahun)
                      ->whereMonth('tanggal_transaksi', $bulan);
                $title = 'Laporan Bulanan — ' . \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y');
                break;
        }
        $transaksis = $query->with('karyawan')->orderByDesc('tanggal_transaksi')->get();
        $totalOmset  = $transaksis->sum('total_harga');
        $totalModal  = $transaksis->sum('total_modal');
        $totalProfit = $totalOmset - $totalModal;

        return view('laporan.index', compact(
            'transaksis', 'totalOmset', 'totalModal',
            'totalProfit', 'periode', 'tahun', 'bulan', 'title'
        ));
    }
    public function export(Request $request)
    {
        return redirect()->route('laporan.index')->with('info', 'Fitur export segera hadir.');
    }
}
