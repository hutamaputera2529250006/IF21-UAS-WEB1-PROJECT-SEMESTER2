@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Kategori Produk')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4" style="font-size:13px; color:#6b7280;">
    <a href="{{ route('kategori.index') }}" style="color:#6b7280; text-decoration:none;">
        <i class="bi bi-tags me-1"></i>Kategori
    </a>
    <i class="bi bi-chevron-right" style="font-size:11px;"></i>
    <span style="color:#111827; font-weight:600;">Edit</span>
</div>

<div class="card" style="max-width:540px;">
    <div class="card-hd">
        <i class="bi bi-pencil-square" style="color:#2563eb;"></i>
        Edit Kategori
    </div>
    <div class="p-4">
        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori <span style="color:#dc2626;">*</span></label>
                <input type="text"
                    name="nama_kategori"
                    class="form-control @error('nama_kategori') is-invalid @enderror"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                    autofocus>
                @error('nama_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Deskripsi <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                <textarea name="deskripsi"
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        rows="3">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update
                </button>
                <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
