<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Karyawan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Extension\DescriptionList\Renderer\DescriptionTermRenderer;

use function GuzzleHttp\describe_type;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = Transaksi::with('karyawan')->orderByDesc('tanggal_transaksi')->paginate(10);
        return view('transaksi.index',compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();
        $produks = Produk::with('kategori')->orderBy('nama_produk')->get();
        $kodeTransaksi = 'TRX-'.now()->format('Ymd').'-'.str_pad(
            Transaksi::whereDate('created_at',today())->count()+1,3,'0',STR_PAD_LEFT
        );
        return view('transaksi.create',compact('karyawans','produks','kodeTransaksi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'=>'required|exists:karyawan,id',
            'kode_transaksi'=>'required|string|unique:transaksis',
            'tanggal_transaksi'=>'required|date',
            'keterangan'=>'nullable|string',
            'produk_id'=>'required|array|min:1',
            'produk_id.*'=>'exists:produks,id',
            'jumlah.*'=>'required|integer|min:1',
        ]);
        DB::transaction(function() use($request){
            $totalHarga =0;
            $totalModal=0;

            $transaksi = Transaksi::create([
                'karyawan_id'=>$request->karyawan_id,
                'kode_transaksi'=>$request->kode_transaksi,
                'tanggal_transaksi'=>$request->tanggal_transaksi,
                'total_harga'=>0,
                'total_modal'=>0,
                'keterangan'=>$request->keterangan,
            ]);
            foreach($request->produk_id as $i =>$produkId){
                $produk = Produk::findOrFail($produkId);
                $jumlah=$request->jumlah[$i];
                $subtotal=$produk->harga_jual*$jumlah;

                DetailTransaksi::create([
                    'transaksi_id'=>$transaksi->id,
                    'produk_id'=>$produkId,
                    'jumlah'=>$jumlah,
                    'harga_jual'=>$produk->harga_jual,
                    'harga_beli'=>$produk->harga_beli,
                    'subtotal'=>$subtotal,
                ]);

                $produk->decrement('stok',$jumlah);
                $totalHarga += $subtotal;
                $totalModal += $produk->harga_beli * $jumlah;
            }
            $transaksi->update([
                'total_harga'=>$totalHarga,
                'total_modal'=>$totalModal,
            ]);
        });
        return redirect()->route('transaksi.index')->with('success','Transaksi berhasil disimpan');

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('karyawan','detailTransaksis.produk');
        return view('transaksi.show',compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        return redirect()->route('transaksi.index')->with('info','Transaksi tidak dapat diedit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        return redirect()->route('transaksi.index')->with('info','Transaksi tidak dapat diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function() use($transaksi){
            foreach($transaksi->detailTransaksis as $detail){
                $detail->produk->increment('stok',$detail->jumlah);
            }
            $transaksi->detailTransaksi()->delete();
            $transaksi->delete();
        });
        return redirect()->route('transaksi.index')->with('success','Transaksi berhasil dihapus');
    }
}
