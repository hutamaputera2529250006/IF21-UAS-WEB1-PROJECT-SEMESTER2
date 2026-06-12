<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'monthly');
        $tahun   = $request->input('tahun', now()->year);
        $bulan   = $request->input('bulan', now()->month);

        $query = Transaksi::query();

        switch ($periode) {
            case 'daily':
                $tanggal = $request->input('tanggal', today()->toDateString());
                $query->whereDate('tanggal_transaksi', $tanggal);
                $title = 'Laporan Harian — ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
                break;

            case 'weekly':
                $dari   = $request->input('dari',   now()->startOfWeek()->toDateString());
                $sampai = $request->input('sampai', now()->endOfWeek()->toDateString());
                $query->whereBetween('tanggal_transaksi', [$dari, $sampai]);
                $title = 'Laporan Mingguan — '
                    . \Carbon\Carbon::parse($dari)->format('d M')
                    . ' s/d '
                    . \Carbon\Carbon::parse($sampai)->format('d M Y');
                break;

            case 'yearly':
                $query->whereYear('tanggal_transaksi', $tahun);
                $title = 'Laporan Tahunan — ' . $tahun;
                break;

            default:
                $query->whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', $bulan);
                $title = 'Laporan Bulanan — '
                    . \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y');
                break;
        }

        $transaksis  = $query->with('karyawan')->orderByDesc('tanggal_transaksi')->get();
        $totalOmset  = $transaksis->sum('total_harga');
        $totalModal  = $transaksis->sum('total_modal');
        $totalProfit = $totalOmset - $totalModal;

        $dari   = $request->input('dari',   now()->startOfWeek()->toDateString());
        $sampai = $request->input('sampai', now()->endOfWeek()->toDateString());
        $tanggal = $request->input('tanggal', today()->toDateString());

        return view('laporan.index', compact(
            'transaksis', 'totalOmset', 'totalModal',
            'totalProfit', 'periode', 'tahun', 'bulan', 'title','tanggal','dari','sampai'
        ));
    }

    public function export(Request $request)
    {
        $periode = $request->input('periode', 'monthly');
        $tahun   = $request->input('tahun', now()->year);
        $bulan   = $request->input('bulan', now()->month);

        $query = Transaksi::query();

        switch ($periode) {
            case 'daily':
                $tanggal = $request->input('tanggal', today()->toDateString());
                $query->whereDate('tanggal_transaksi', $tanggal);
                $filename = 'laporan-harian-' . $tanggal;
                break;

            case 'weekly':
                $dari   = $request->input('dari',   now()->startOfWeek()->toDateString());
                $sampai = $request->input('sampai', now()->endOfWeek()->toDateString());
                $query->whereBetween('tanggal_transaksi', [$dari, $sampai]);
                $filename = 'laporan-mingguan-' . $dari . '-sd-' . $sampai;
                break;

            case 'yearly':
                $query->whereYear('tanggal_transaksi', $tahun);
                $filename = 'laporan-tahunan-' . $tahun;
                break;

            default:
                $query->whereYear('tanggal_transaksi', $tahun)->whereMonth('tanggal_transaksi', $bulan);
                $filename = 'laporan-bulanan-' . $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
                break;
        }

        $transaksis = $query->with('karyawan')->orderByDesc('tanggal_transaksi')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($transaksis) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['#', 'Kode Transaksi', 'Tanggal', 'Karyawan', 'Omset', 'Modal', 'Profit'], ';');

            foreach ($transaksis as $i => $t) {
                $profit = $t->total_harga - $t->total_modal;
                fputcsv($handle, [
                    $i + 1,
                    $t->kode_transaksi,
                    \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y'),
                    $t->karyawan->nama_karyawan ?? '-',
                    $t->total_harga,
                    $t->total_modal,
                    $profit,
                ], ';');
            }

            fputcsv($handle, [
                '', 'TOTAL', '', '',
                $transaksis->sum('total_harga'),
                $transaksis->sum('total_modal'),
                $transaksis->sum('total_harga') - $transaksis->sum('total_modal'),
            ], ';');

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
