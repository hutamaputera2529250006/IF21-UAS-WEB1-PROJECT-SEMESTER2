@extends('layouts.app')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Karyawan')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4" style="font-size:13px; color:#6b7280;">
    <a href="{{ route('karyawan.index') }}" style="color:#6b7280; text-decoration:none;">
        <i class="bi bi-people me-1"></i>Karyawan
    </a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span style="color:#111827; font-weight:600;">Tambah</span>
</div>

<div class="card" style="max-width:560px;">
    <div class="card-hd">
        <i class="bi bi-person-plus" style="color:#2563eb;"></i>
        Tambah Karyawan Baru
    </div>
    <div class="p-4">
        <form action="{{ route('karyawan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Karyawan <span style="color:#dc2626;">*</span></label>
                <input type="text"
                    name="nama_karyawan"
                    class="form-control @error('nama_karyawan') is-invalid @enderror"
                    value="{{ old('nama_karyawan') }}"
                    placeholder="Nama lengkap karyawan"
                    autofocus>
                @error('nama_karyawan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan <span style="color:#dc2626;">*</span></label>
                <input type="text"
                    name="jabatan"
                    class="form-control @error('jabatan') is-invalid @enderror"
                    value="{{ old('jabatan') }}"
                    placeholder="Contoh: Kasir, Admin, Gudang...">
                @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">No. Telepon <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                <input type="text"
                    name="no_telepon"
                    class="form-control @error('no_telepon') is-invalid @enderror"
                    value="{{ old('no_telepon') }}"
                    placeholder="08xxxxxxxxxx">
                @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                <textarea name="alamat"
                        class="form-control @error('alamat') is-invalid @enderror"
                        rows="2"
                        placeholder="Alamat lengkap karyawan...">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Tanggal Masuk <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                <input type="date"
                    name="tanggal_masuk"
                    class="form-control @error('tanggal_masuk') is-invalid @enderror"
                    value="{{ old('tanggal_masuk') }}"
                    style="max-width:220px;">
                @error('tanggal_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

@endsection
