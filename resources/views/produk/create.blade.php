@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Produk')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4" style="font-size:13px; color:#6b7280;">
    <a href="{{ route('produk.index') }}" style="color:#6b7280; text-decoration:none;">
        <i class="bi bi-box-seam me-1"></i>Produk
    </a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span style="color:#111827; font-weight:600;">Tambah</span>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-hd">
        <i class="bi bi-plus-circle" style="color:#2563eb;"></i>
        Tambah Produk Baru
    </div>
    <div class="p-4">
        <form action="{{ route('produk.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Produk <span style="color:#dc2626;">*</span></label>
                <input type="text"
                    name="nama_produk"
                    class="form-control @error('nama_produk') is-invalid @enderror"
                    value="{{ old('nama_produk') }}"
                    placeholder="Contoh: Semen Tiga Roda 50kg"
                    autofocus>
                @error('nama_produk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori <span style="color:#dc2626;">*</span></label>
                <select name="kategori_id"
                        class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">Harga Beli <span style="color:#dc2626;">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="font-size:13px; background:#f9fafb; border-color:#e5e7eb;">Rp</span>
                        <input type="number"
                            name="harga_beli"
                            class="form-control @error('harga_beli') is-invalid @enderror"
                            value="{{ old('harga_beli') }}"
                            placeholder="0"
                            min="0">
                        @error('harga_beli')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label">Harga Jual <span style="color:#dc2626;">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" style="font-size:13px; background:#f9fafb; border-color:#e5e7eb;">Rp</span>
                        <input type="number"
                            name="harga_jual"
                            class="form-control @error('harga_jual') is-invalid @enderror"
                            value="{{ old('harga_jual') }}"
                            placeholder="0"
                            min="0">
                        @error('harga_jual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok Awal <span style="color:#dc2626;">*</span></label>
                <input type="number"
                    name="stok"
                    class="form-control @error('stok') is-invalid @enderror"
                    value="{{ old('stok', 0) }}"
                    min="0"
                    style="max-width:180px;">
                @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Deskripsi <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                <textarea name="deskripsi"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        rows="3"
                        placeholder="Deskripsi singkat produk...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>

@endsection
