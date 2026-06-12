@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="mb-1 fw-bold">Detail Transaksi</h4>
        <p class="text-muted mb-0 small">
            <i class="bi bi-tag me-1"></i>
            <span class="fw-semibold text-primary">{{ $transaksi->kode_transaksi }}</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST"
            onsubmit="return confirm('Hapus transaksi ini? Stok produk akan dikembalikan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div class="row g-4 no-print">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Info Transaksi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted ps-0" style="width: 40%">Kode</td>
                        <td class="fw-semibold text-primary">{{ $transaksi->kode_transaksi }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Tanggal</td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Karyawan</td>
                        <td>{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Jabatan</td>
                        <td>{{ $transaksi->karyawan->jabatan ?? '-' }}</td>
                    </tr>
                    @if ($transaksi->keterangan)
                        <tr>
                            <td class="text-muted ps-0">Keterangan</td>
                            <td>{{ $transaksi->keterangan }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer bg-light">
                @php $profit = $transaksi->total_harga - $transaksi->total_modal; @endphp
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Total Modal</span>
                    <span>Rp {{ number_format($transaksi->total_modal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Total Harga</span>
                    <span class="fw-bold text-primary">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="small fw-medium">Profit</span>
                    <span class="fw-bold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($profit, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-cart me-2 text-primary"></i>
                    Produk Terjual
                    <span class="badge bg-primary ms-1">{{ $transaksi->detailTransaksis->count() }} item</span>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Produk</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->detailTransaksis as $i => $detail)
                                @php $profitItem = ($detail->harga_jual - $detail->harga_beli) * $detail->jumlah; @endphp
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-medium">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                        <small class="text-muted">
                                            Profit/item: Rp {{ number_format($detail->harga_jual - $detail->harga_beli, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td class="text-end text-muted small">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $detail->jumlah }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                        <small class="{{ $profitItem >= 0 ? 'text-success' : 'text-danger' }}">
                                            +Rp {{ number_format($profitItem, 0, ',', '.') }}
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="ps-4 fw-semibold">Total</td>
                                <td class="text-center fw-semibold">{{ $transaksi->detailTransaksis->sum('jumlah') }} pcs</td>
                                <td class="text-end pe-4 fw-bold text-primary">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Nota
            </button>
        </div>
    </div>
</div>

@php $profit = $transaksi->total_harga - $transaksi->total_modal; @endphp

<div class="struk-print">
    <div class="struk-header">
        <div class="struk-toko">Sinar Mulia</div>
        <div class="struk-alamat">Toko Bahan Bangunan</div>
        <div class="struk-alamat">Telp: 0811-6666-8888</div>
        <div class="struk-divider">--------------------------------</div>
    </div>

    <div class="struk-info">
        <div class="struk-row">
            <span>No.</span>
            <span>{{ $transaksi->kode_transaksi }}</span>
        </div>
        <div class="struk-row">
            <span>Tanggal</span>
            <span>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</span>
        </div>
        <div class="struk-row">
            <span>Kasir</span>
            <span>{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</span>
        </div>
    </div>

    <div class="struk-divider">--------------------------------</div>

    <div class="struk-items">
        @foreach ($transaksi->detailTransaksis as $i => $detail)
            <div class="struk-item-name">{{ $i + 1 }}. {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</div>
            <div class="struk-row">
                <span>{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="struk-divider">--------------------------------</div>

    <div class="struk-row">
        <span>Total QTY</span>
        <span>{{ $transaksi->detailTransaksis->sum('jumlah') }} pcs</span>
    </div>
    <div class="struk-row">
        <span>Sub Total</span>
        <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="struk-divider">================================</div>

    <div class="struk-row struk-total">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="struk-divider">================================</div>

    <div class="struk-footer">
        <p>Terima kasih telah berbelanja</p>
        <p>Barang yang sudah dibeli</p>
        <p>tidak dapat dikembalikan</p>
    </div>
</div>

<style>
.struk-print {
    display: none;
}

.struk-print {
    font-family: 'Courier New', Courier, monospace;
    font-size: 12px;
    width: 72mm;
    margin: 0 auto;
    padding: 8mm 4mm;
    color: #000;
    background: white;
    line-height: 1.5;
}

.struk-header {
    text-align: center;
    margin-bottom: 4px;
}

.struk-toko {
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 1px;
    margin-bottom: 2px;
}

.struk-alamat {
    font-size: 11px;
    margin-bottom: 4px;
}

.struk-divider {
    text-align: center;
    font-size: 11px;
    margin: 4px 0;
    color: #333;
}

.struk-info {
    margin: 4px 0;
}

.struk-row {
    display: flex;
    justify-content: space-between;
    margin: 2px 0;
}

.struk-items {
    margin: 4px 0;
}

.struk-item-name {
    font-weight: bold;
    margin-top: 4px;
    margin-bottom: 1px;
    word-break: break-word;
}

.struk-total {
    font-weight: bold;
    font-size: 14px;
    margin-top: 4px;
}

.struk-footer {
    text-align: center;
    margin-top: 8px;
    font-size: 11px;
    line-height: 1.6;
}

.struk-footer p {
    margin: 0;
}

@media print {
    @page {
        margin: 5mm;
        size: 80mm auto;
    }

    body * {
        visibility: hidden;
    }

    .struk-print,
    .struk-print * {
        visibility: visible;
    }

    .struk-print {
        display: block !important;
        position: absolute;
        top: 0;
        left: 0;
    }
}
</style>
@endsection
