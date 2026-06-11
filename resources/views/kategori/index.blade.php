@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('page-title', 'Kategori Produk')

@push('styles')
<style>
    .page-hd {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .page-hd h1 { font-size: 20px; font-weight: 700; margin: 0; }

    .empty-state {
        text-align: center;
        padding: 52px 24px;
        color: #9ca3af;
    }
    .empty-state i { font-size: 38px; opacity: .35; display: block; margin-bottom: 10px; }
    .empty-state p { font-size: 13.5px; margin: 0; }
</style>
@endpush

@section('content')

<div class="page-hd">
    <h1>Kategori Produk</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    @if($kategoris->isEmpty())
        <div class="empty-state">
            <i class="bi bi-tags"></i>
            <p>Belum ada kategori. <a href="{{ route('kategori.create') }}">Tambah sekarang</a></p>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th width="80">Produk</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $i => $k)
                    <tr>
                        <td style="color:#9ca3af; font-size:12px;">{{ $i + 1 }}</td>
                        <td style="font-weight:600;">{{ $k->nama_kategori }}</td>
                        <td style="color:#6b7280;">{{ $k->deskripsi ?? '—' }}</td>
                        <td>
                            <span class="badge" style="background:#eff6ff; color:#2563eb;">
                                {{ $k->produks_count ?? $k->produks->count() }} produk
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori \'{{ $k->nama_kategori }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">
                {{ $kategoris->links() }}
            </div>
        </div>
    @endif
</div>

@endsection
