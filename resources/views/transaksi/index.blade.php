@extends('layouts.app')

@section('title', 'Data Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Transaksi Penjualan</h4>
        <p class="text-muted mb-0 small">Daftar semua transaksi yang telah dicatat</p>
    </div>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 50px">#</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th class="text-end">Total Harga</th>
                        <th class="text-end">Modal</th>
                        <th class="text-end">Profit</th>
                        <th class="text-center" style="width: 130px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $i => $transaksi)
                        @php
                            $profit = $transaksi->total_harga - $transaksi->total_modal;
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">{{ $transaksis->firstItem() + $i }}</td>
                            <td>
                                <span class="fw-semibold text-primary">{{ $transaksi->kode_transaksi }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                            </td>
                            <td>{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</td>
                            <td class="text-end fw-semibold">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="text-end text-muted">
                                Rp {{ number_format($transaksi->total_modal, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <span class="fw-semibold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($profit, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                    class="btn btn-sm btn-outline-info me-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Hapus transaksi {{ $transaksi->kode_transaksi }}? Stok produk akan dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-2 d-block mb-2"></i>
                                Belum ada transaksi yang tercatat.
                                <a href="{{ route('transaksi.create') }}" class="d-block mt-2">Tambah transaksi pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($transaksis->hasPages())
        <div class="card-footer bg-white border-top-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }}
                    dari {{ $transaksis->total() }} transaksi
                </small>
                {{ $transaksis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
