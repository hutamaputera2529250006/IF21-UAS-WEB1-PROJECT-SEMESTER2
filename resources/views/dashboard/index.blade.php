@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>

    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    @media (max-width: 992px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stat-grid { grid-template-columns: 1fr 1fr; gap: 12px; } }

    .scard {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: box-shadow .15s;
    }
    .scard:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }

    .scard-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .scard-label { font-size: 11.5px; color: #6b7280; font-weight: 500; margin-bottom: 3px; }
    .scard-val   { font-size: 22px; font-weight: 700; line-height: 1; letter-spacing: -0.02em; }

    .fin-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
    @media (max-width: 768px) { .fin-grid { grid-template-columns: 1fr; } }

    .fcard {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px 20px;
        border-left-width: 4px;
    }
    .fcard-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .fcard-val { font-size: 21px; font-weight: 700; letter-spacing: -0.02em; line-height: 1.1; }
    .fcard-sub { font-size: 11.5px; color: #9ca3af; margin-top: 4px; }

    .section-hd {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }
    .section-hd h2 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .profit-pos { color: #16a34a; font-weight: 600; }
    .profit-neg { color: #dc2626; font-weight: 600; }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #9ca3af;
    }
    .empty-state i { font-size: 36px; opacity: .4; display: block; margin-bottom: 10px; }
    .empty-state p { font-size: 13.5px; margin: 0; }
</style>
@endpush

@section('content')

<div class="stat-grid">

    <div class="scard">
        <div class="scard-icon" style="background:#eff6ff; color:#2563eb;">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div>
            <div class="scard-label">Kategori</div>
            <div class="scard-val">{{ $totalKategori }}</div>
        </div>
    </div>

    <div class="scard">
        <div class="scard-icon" style="background:#fffbeb; color:#d97706;">
            <i class="bi bi-box-seam-fill"></i>
        </div>
        <div>
            <div class="scard-label">Produk</div>
            <div class="scard-val">{{ $totalProduk }}</div>
        </div>
    </div>

    <div class="scard">
        <div class="scard-icon" style="background:#f0fdf4; color:#16a34a;">
            <i class="bi bi-people-fill"></i>
        </div>
        <div>
            <div class="scard-label">Karyawan</div>
            <div class="scard-val">{{ $totalKaryawan }}</div>
        </div>
    </div>

    <div class="scard">
        <div class="scard-icon" style="background:#fdf4ff; color:#9333ea;">
            <i class="bi bi-receipt-cutoff"></i>
        </div>
        <div>
            <div class="scard-label">Transaksi</div>
            <div class="scard-val">{{ $totalTransaksi }}</div>
        </div>
    </div>

</div>

@php
    $bulan = \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y');
@endphp

<div class="fin-grid">

    <div class="fcard" style="border-left-color:#2563eb;">
        <div class="fcard-label"><i class="bi bi-cash-stack"></i> Omset Bulan Ini</div>
        <div class="fcard-val" style="color:#2563eb;">
            Rp {{ number_format($omsetBulanIni, 0, ',', '.') }}
        </div>
        <div class="fcard-sub">Total penjualan {{ $bulan }}</div>
    </div>

    <div class="fcard" style="border-left-color:#d97706;">
        <div class="fcard-label"><i class="bi bi-wallet2"></i> Modal Bulan Ini</div>
        <div class="fcard-val" style="color:#d97706;">
            Rp {{ number_format($modalBulanIni, 0, ',', '.') }}
        </div>
        <div class="fcard-sub">Harga pokok penjualan</div>
    </div>

    <div class="fcard" style="border-left-color:#16a34a;">
        <div class="fcard-label"><i class="bi bi-graph-up-arrow"></i> Profit Bulan Ini</div>
        <div class="fcard-val" style="color:#16a34a;">
            Rp {{ number_format($profitBulanIni, 0, ',', '.') }}
        </div>
        <div class="fcard-sub">Omset &minus; Modal</div>
    </div>

</div>

<div class="section-hd">
    <h2><i class="bi bi-clock-history" style="color:#2563eb;"></i> Transaksi Terbaru</h2>
    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-primary">
        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>

<div class="card">
    @if($transaksiTerbaru->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>Belum ada transaksi yang tercatat</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Total</th>
                        <th>Profit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiTerbaru as $i => $t)
                    @php
                        $profit = $t->total_harga - $t->total_modal;
                    @endphp
                    <tr>
                        <td style="color:#9ca3af; font-size:12px;">{{ $i + 1 }}</td>
                        <td><span class="mono">{{ $t->kode_transaksi }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}</td>
                        <td>{{ $t->karyawan->nama_karyawan ?? '—' }}</td>
                        <td style="font-weight:600;">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="{{ $profit >= 0 ? 'profit-pos' : 'profit-neg' }}">
                                Rp {{ number_format($profit, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('transaksi.show', $t->id) }}"
                                class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
