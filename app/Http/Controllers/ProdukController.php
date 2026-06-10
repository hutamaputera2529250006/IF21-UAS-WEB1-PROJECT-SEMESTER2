<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::with('kategori')->oderBy('nama_produk')->paginate(10);
        return view('produk.index',compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('produk.create',compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'=>'required|exists:kategori,id',
            'nama_produk'=>'required|string|max:150',
            'harga_beli'=>'required|numeric|min:0',
            'harga_jual'=>'required|numeric|min:0',
            'stok'=>'required|integer|min:0',
            'deskripsi'=>'nullable|string',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return view('produk.show',compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('produk.edit',compact('produk','kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kategori_id'=>'required|exist:kategori,id',
            'nama_produk'=>'required|string|max:150',
            'harga_beli'=>'required|numeric|min:0',
            'harga_jual'=>'required|numeric|min:0',
            'stok'=>'required|integer|min:0',
            'deskripsi'=>'nullable|string',
        ]);

        $produk->update($request->only(
            'kategori_id','nama_produk','harga_beli','harga_jual','stok','deskripsi'
        ));

        return redirect()->route('produk.index')->with('success','Produk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success','Produk berhasil dihapus');
    }
}
