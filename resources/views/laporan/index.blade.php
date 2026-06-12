@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Laporan Penjualan</h4>
        <p class="text-muted mb-0 small">{{ $title ?? 'Ringkasan omset, modal, dan profit' }}</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end" id="filterForm">

            <div class="col-md-3">
                <label class="form-label fw-medium small">Periode</label>
                <select name="periode" class="form-select" id="filterPeriode">
                    <option value="daily"   {{ $periode === 'daily'   ? 'selected' : '' }}>Harian</option>
                    <option value="weekly"  {{ $periode === 'weekly'  ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $periode === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly"  {{ $periode === 'yearly'  ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            <div class="col-md-3 filter-field" id="field-daily"
                style="{{ $periode === 'daily' ? '' : 'display:none' }}">
                <label class="form-label fw-medium small">Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                    value="{{ request('tanggal', date('Y-m-d')) }}">
            </div>

            <div class="col-md-5 filter-field" id="field-weekly"
                style="{{ $periode === 'weekly' ? '' : 'display:none' }}">
                <label class="form-label fw-medium small">
                    Range Tanggal
                    <span class="text-muted fw-normal">(min. 7 hari)</span>
                </label>
                <div class="d-flex gap-2 align-items-center">
                    <input type="date" name="dari" id="inputDari" class="form-control"
                        value="{{ request('dari', now()->startOfWeek()->format('Y-m-d')) }}">
                    <span class="text-muted small fw-semibold">s/d</span>
                    <input type="date" name="sampai" id="inputSampai" class="form-control"
                        value="{{ request('sampai', now()->endOfWeek()->format('Y-m-d')) }}">
                </div>
                <div id="weeklyError" class="text-danger small mt-1" style="display:none">
                    <i class="bi bi-exclamation-circle me-1"></i>Range minimal 7 hari.
                </div>
            </div>

            <div class="col-md-3 filter-field" id="field-monthly"
                style="{{ $periode === 'monthly' ? '' : 'display:none' }}">
                <label class="form-label fw-medium small">Bulan</label>
                <div class="row g-2">
                    <div class="col-7">
                        <select name="bulan" class="form-select">
                            @foreach(range(1,12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-5">
                        <select name="tahun" class="form-select">
                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-3 filter-field" id="field-yearly"
                style="{{ $periode === 'yearly' ? '' : 'display:none' }}">
                <label class="form-label fw-medium small">Tahun</label>
                <select name="tahun" class="form-select">
                    @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                    <i class="bi bi-cash-stack fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Omset</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($totalOmset, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-box-seam fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Modal</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 {{ $totalProfit >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10">
                    <i class="bi bi-graph-up-arrow fs-4 {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Profit</div>
                    <div class="fw-bold fs-5 {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($totalProfit, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-table me-2 text-primary"></i>
            Detail Transaksi
            <span class="badge bg-secondary ms-1">{{ $transaksis->count() }}</span>
        </h6>
        <a href="{{ route('laporan.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Export CSV
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th class="text-end">Omset</th>
                        <th class="text-end">Modal</th>
                        <th class="text-end pe-4">Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $i => $transaksi)
                        @php $profitRow = $transaksi->total_harga - $transaksi->total_modal; @endphp
                        <tr>
                            <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                    class="text-primary fw-semibold text-decoration-none">
                                    {{ $transaksi->kode_transaksi }}
                                </a>
                            </td>
                            <td class="text-muted small">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                            </td>
                            <td>{{ $transaksi->karyawan->nama_karyawan ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td class="text-end text-muted">Rp {{ number_format($transaksi->total_modal, 0, ',', '.') }}</td>
                            <td class="text-end pe-4">
                                <span class="fw-semibold {{ $profitRow >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($profitRow, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-bar-chart fs-2 d-block mb-2"></i>
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count() > 0)
                    <tfoot class="table-light fw-semibold">
                        <tr>
                            <td colspan="4" class="ps-4">Total</td>
                            <td class="text-end">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                            <td class="text-end text-muted">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                            <td class="text-end pe-4 {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($totalProfit, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<script>
    const filterSelect = document.getElementById('filterPeriode');
    const allFields = document.querySelectorAll('.filter-field');

    filterSelect.addEventListener('change', function () {
        allFields.forEach(el => el.style.display = 'none');
        const target = document.getElementById('field-' + this.value);
        if (target) target.style.display = '';
    });

    const inputDari    = document.getElementById('inputDari');
    const inputSampai  = document.getElementById('inputSampai');
    const weeklyError  = document.getElementById('weeklyError');
    const filterForm   = document.getElementById('filterForm');

    function validateWeeklyRange() {
        if (!inputDari || !inputSampai) return true;
        const dari   = new Date(inputDari.value);
        const sampai = new Date(inputSampai.value);
        const diffDays = (sampai - dari) / (1000 * 60 * 60 * 24);
        return diffDays >= 6;
    }

    if (inputDari) {
        inputDari.addEventListener('change', function () {
            const dari = new Date(this.value);
            dari.setDate(dari.getDate() + 6);
            const minSampai = dari.toISOString().split('T')[0];
            inputSampai.min = minSampai;
            if (inputSampai.value && inputSampai.value < minSampai) {
                inputSampai.value = minSampai;
            }
            weeklyError.style.display = validateWeeklyRange() ? 'none' : '';
        });
    }

    if (inputSampai) {
        inputSampai.addEventListener('change', function () {
            weeklyError.style.display = validateWeeklyRange() ? 'none' : '';
        });
    }

    filterForm.addEventListener('submit', function (e) {
        if (filterSelect.value === 'weekly' && !validateWeeklyRange()) {
            e.preventDefault();
            weeklyError.style.display = '';
            inputSampai.focus();
        }
    });
</script>
@endsection
