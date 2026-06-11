@extends('layouts.app')

@section('title', 'Produk')
@section('page-title', 'Produk')

@push('styles')
<style>
    .page-hd {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .page-hd h1 { font-size: 20px; font-weight: 700; margin: 0; }

    .stok-ok      { color: #16a34a; font-weight: 600; }
    .stok-low     { color: #d97706; font-weight: 600; }
    .stok-empty   { color: #dc2626; font-weight: 600; }

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
    <h1>Produk</h1>
    <a href="{{ route('produk.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

<div class="card">
    @if($produks->isEmpty())
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <p>Belum ada produk. <a href="{{ route('produk.create') }}">Tambah sekarang</a></p>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produks as $i => $p)
                    <tr>
                        <td style="color:#9ca3af; font-size:12px;">{{ $produks->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $p->nama_produk }}</td>
                        <td>
                            <span class="badge" style="background:#eff6ff; color:#2563eb;">
                                {{ $p->kategori->nama_kategori ?? '—' }}
                            </span>
                        </td>
                        <td style="color:#6b7280;">Rp {{ number_format($p->harga_beli, 0, ',', '.') }}</td>
                        <td style="font-weight:600;">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            @if($p->stok == 0)
                                <span class="stok-empty"><i class="bi bi-x-circle me-1"></i>{{ $p->stok }}</span>
                            @elseif($p->stok <= 5)
                                <span class="stok-low"><i class="bi bi-exclamation-circle me-1"></i>{{ $p->stok }}</span>
                            @else
                                <span class="stok-ok">{{ $p->stok }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('produk.edit', $p->id) }}"class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('produk.destroy', $p->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus produk \'{{ $p->nama_produk }}\'?')">
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
                {{ $produks->links() }}
            </div>
        </div>
    @endif
</div>

@endsection
