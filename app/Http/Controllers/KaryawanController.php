<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = Karyawan::orderBy('nama_karyawan')->paginate(10);
        return view('karyawan.index',compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan'=>'required|string|max:100',
            'jabatan'=>'required|string|max:100',
            'no_telepon'=>'nullable|string|max:20',
            'alamat'=>'nullable|string',
            'tanggal_masuk'=>'nullable|date',
        ]);

        Karyawan::create($request->only(
            'nama_karyawan','jabatan','no_telepon','alamat','tanggal_masuk'
        ));

        return redirect()->route('karyawan.index')->with('success','Karyawan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        return view('karyawan.show',compact('karyawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit',compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama_karyawan'=>'required|string|max:100',
            'jabatan'=>'required|string|max:100',
            'no_telepon'=>'nullable|string|max:20',
            'alamat'=>'nullable|string',
            'tanggal_masuk'=>'nullable|date',
        ]);

        $karyawan->update($request->only(
            'nama_karyawan','jabatan','no_telepon','alamat','tanggal_masuk'
        ));

        return redirect()->route('karyawan.index')->with('success','Karyawan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success','Karyawan berhasil dihapus');
    }
}
