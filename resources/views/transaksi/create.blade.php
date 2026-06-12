@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Tambah Transaksi</h4>
        <p class="text-muted mb-0 small">Isi data transaksi dan pilih produk yang dijual</p>
    </div>
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
    @csrf

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Info Transaksi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="kode_transaksi" class="form-label fw-medium">Kode Transaksi</label>
                        <input type="text" class="form-control @error('kode_transaksi') is-invalid @enderror"
                            id="kode_transaksi" name="kode_transaksi"
                            value="{{ old('kode_transaksi', $kodeTransaksi) }}"
                            placeholder="Contoh: TRX-20260612-001">
                        @error('kode_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Kode otomatis dibuat, bisa diubah manual.</div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_transaksi" class="form-label fw-medium">Tanggal Transaksi</label>
                        <input type="date" class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                            id="tanggal_transaksi" name="tanggal_transaksi"
                            value="{{ old('tanggal_transaksi', date('Y-m-d')) }}">
                        @error('tanggal_transaksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="karyawan_id" class="form-label fw-medium">Kasir / Karyawan</label>
                        <select class="form-select @error('karyawan_id') is-invalid @enderror"
                            id="karyawan_id" name="karyawan_id">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach ($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}"
                                    {{ old('karyawan_id') == $karyawan->id ? 'selected' : '' }}>
                                    {{ $karyawan->nama_karyawan }} ({{ $karyawan->jabatan }})
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-medium">
                            Keterangan <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <textarea class="form-control" id="keterangan" name="keterangan"
                            rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Total Modal</span>
                        <span id="summaryModal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-semibold">Total Harga</span>
                        <span class="fw-bold text-primary fs-5" id="summaryHarga">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between text-success small">
                        <span>Estimasi Profit</span>
                        <span id="summaryProfit">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-cart me-2 text-primary"></i>Produk Dijual</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahItem">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="tabelProduk">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Produk</th>
                                    <th style="width:80px">Stok</th>
                                    <th style="width:100px">Jumlah</th>
                                    <th style="width:130px">Harga Jual</th>
                                    <th style="width:120px">Subtotal</th>
                                    <th style="width:45px"></th>
                                </tr>
                            </thead>
                            <tbody id="itemContainer"></tbody>
                        </table>
                    </div>
                    <div id="emptyState" class="text-center py-5 text-muted">
                        <i class="bi bi-cart-x fs-2 d-block mb-2"></i>
                        Belum ada produk. Klik <strong>+ Tambah Produk</strong> untuk memulai.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-5" id="btnSimpan" disabled>
                    <i class="bi bi-save me-1"></i> Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    const produkData = @json($produks);

    let itemIndex = 0;

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka).toLocaleString('id-ID');
    }

    function updateSummary() {
        let totalHarga = 0;
        let totalModal = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            totalHarga += parseFloat(row.dataset.subtotal || 0);
            totalModal += parseFloat(row.dataset.modal || 0);
        });

        document.getElementById('summaryHarga').textContent = formatRupiah(totalHarga);
        document.getElementById('summaryModal').textContent = formatRupiah(totalModal);
        document.getElementById('summaryProfit').textContent = formatRupiah(totalHarga - totalModal);

        const hasItems = document.querySelectorAll('.item-row').length > 0;
        document.getElementById('btnSimpan').disabled = !hasItems;
        document.getElementById('emptyState').style.display = hasItems ? 'none' : 'block';
    }

    function tambahItem() {
        const idx = itemIndex++;
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.dataset.subtotal = 0;
        tr.dataset.modal = 0;

        const options = produkData.map(p =>
            `<option value="${p.id}" data-stok="${p.stok}" data-beli="${p.harga_beli}" data-jual="${p.harga_jual}">${p.nama} (Stok: ${p.stok})</option>`
        ).join('');

        tr.innerHTML = `
            <td class="ps-3" style="min-width:0">
                <select class="form-select form-select-sm produk-select" name="items[${idx}][produk_id]" required
                    style="max-width:100%; overflow:hidden; text-overflow:ellipsis;">
                    <option value="">-- Pilih Produk --</option>
                    ${options}
                </select>
            </td>
            <td style="width:80px">
                <span class="stok-info badge bg-light text-dark border">-</span>
            </td>
            <td style="width:100px">
                <input type="number" class="form-control form-control-sm jumlah-input"
                    name="items[${idx}][jumlah]" min="1" value="1" required disabled>
            </td>
            <td style="width:130px">
                <input type="number" class="form-control form-control-sm harga-input"
                    name="items[${idx}][harga_jual]" min="0" step="100" required disabled>
            </td>
            <td style="width:120px">
                <span class="fw-semibold text-primary subtotal-label">-</span>
            </td>
            <td style="width:45px">
                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        document.getElementById('itemContainer').appendChild(tr);

        const select   = tr.querySelector('.produk-select');
        const jumlah   = tr.querySelector('.jumlah-input');
        const harga    = tr.querySelector('.harga-input');
        const stokInfo = tr.querySelector('.stok-info');
        const label    = tr.querySelector('.subtotal-label');

        function hitung() {
            const opt      = select.selectedOptions[0];
            const jml      = parseInt(jumlah.value) || 0;
            const hrg      = parseFloat(harga.value) || 0;
            const beli     = opt ? parseFloat(opt.dataset.beli) || 0 : 0;
            const subtotal = jml * hrg;
            const modal    = jml * beli;

            label.textContent   = formatRupiah(subtotal);
            tr.dataset.subtotal = subtotal;
            tr.dataset.modal    = modal;
            updateSummary();
        }

        select.addEventListener('change', function () {
            const opt = this.selectedOptions[0];
            if (opt && opt.value !== '') {
                const stok = parseInt(opt.dataset.stok);
                stokInfo.textContent = stok;
                stokInfo.className = `stok-info badge ${stok > 0 ? 'bg-success' : 'bg-danger'} text-white`;
                jumlah.max      = stok;
                jumlah.disabled = stok === 0;
                harga.value     = opt.dataset.jual;
                harga.disabled  = false;
                hitung();
            }
        });

        jumlah.addEventListener('input', hitung);
        harga.addEventListener('input', hitung);

        tr.querySelector('.btn-hapus').addEventListener('click', function () {
            tr.remove();
            updateSummary();
        });

        updateSummary();
    }

    document.getElementById('btnTambahItem').addEventListener('click', tambahItem);
    tambahItem();
</script>
@endsection
