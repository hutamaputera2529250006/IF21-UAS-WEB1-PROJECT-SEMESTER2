<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sinarmulia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sb-w: 240px;
            --nb-h: 58px;
            --blue: #2563eb;
            --blue-dk: #1d4ed8;
            --sb-bg: #111827;
            --sb-text: #9ca3af;
            --sb-active-bg: rgba(37,99,235,0.18);
            --sb-active-text: #93c5fd;
            --sb-hover: rgba(255,255,255,0.06);
            --body-bg: #f3f4f6;
            --card: #ffffff;
            --border: #e5e7eb;
            --muted: #6b7280;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            margin: 0;
            color: #111827;
        }

        #sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sb-w);
            background: var(--sb-bg);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            overflow: hidden;
        }

        .sb-brand {
            height: var(--nb-h);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 18px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }
        .sb-logo {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; color: #fff;
            flex-shrink: 0;
        }
        .sb-brand-text {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
            letter-spacing: -0.01em;
        }
        .sb-brand-text small {
            display: block;
            font-size: 10.5px;
            font-weight: 400;
            color: var(--sb-text);
            letter-spacing: 0.02em;
        }

        .sb-label {
            padding: 18px 18px 5px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #4b5563;
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 18px;
            color: var(--sb-text);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 0;
            transition: background .12s, color .12s;
            position: relative;
        }
        .sb-link i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }
        .sb-link:hover { background: var(--sb-hover); color: #e5e7eb; }
        .sb-link.active {
            background: var(--sb-active-bg);
            color: var(--sb-active-text);
        }
        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 4px; bottom: 4px;
            width: 3px;
            background: #3b82f6;
            border-radius: 0 3px 3px 0;
        }

        .sb-footer {
            margin-top: auto;
            padding: 14px 18px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sb-avatar {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--blue);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .sb-user-name { font-size: 12.5px; font-weight: 600; color: #e5e7eb; }
        .sb-user-role { font-size: 11px; color: var(--sb-text); }

        #topbar {
            position: fixed;
            top: 0;
            left: var(--sb-w);
            right: 0;
            height: var(--nb-h);
            background: var(--card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1040;
        }
        .tb-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #111827;
        }
        .tb-right { display: flex; align-items: center; gap: 8px; }

        .tb-icon-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 14px;
            transition: all .12s;
            text-decoration: none;
        }
        .tb-icon-btn:hover { background: var(--body-bg); color: #111827; }

        .tb-user {
            display: flex; align-items: center; gap: 7px;
            padding: 3px 10px 3px 3px;
            border-radius: 9px;
            border: 1px solid var(--border);
        }
        .tb-uavatar {
            width: 28px; height: 28px;
            border-radius: 7px;
            background: var(--blue);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff;
        }
        .tb-uname { font-size: 12.5px; font-weight: 600; }

        #content {
            margin-left: var(--sb-w);
            margin-top: var(--nb-h);
            padding: 26px;
            min-height: calc(100vh - var(--nb-h));
        }

        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .flash-success { background: #dcfce7; color: #15803d; }
        .flash-error   { background: #fee2e2; color: #dc2626; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .card-hd {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tbl { width: 100%; border-collapse: collapse; }
        .tbl thead th {
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            background: #f9fafb;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .tbl tbody td {
            padding: 12px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .tbl tbody tr:last-child td { border-bottom: none; }
        .tbl tbody tr:hover td { background: #fafafa; }

        .mono {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 11.5px;
            background: #f3f4f6;
            padding: 2px 7px;
            border-radius: 5px;
            color: #374151;
        }

        .btn { border-radius: 8px; font-size: 13px; font-weight: 600; padding: 7px 14px; }
        .btn-primary { background: var(--blue); border-color: var(--blue); }
        .btn-primary:hover { background: var(--blue-dk); border-color: var(--blue-dk); }
        .btn-sm { padding: 4px 10px; font-size: 12px; }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 13.5px;
            padding: 8px 12px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px; }
        .badge { border-radius: 6px; font-size: 11px; }
    </style>

    @stack('styles')
</head>
<body>

<aside id="sidebar">
    <div class="sb-brand">
        <div class="sb-logo"><i class="bi bi-shop-window"></i></div>
        <div class="sb-brand-text">
            Sinarmulia
            <small>Toko Bangunan</small>
        </div>
    </div>

    <div class="sb-label">Menu</div>
    <a class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <i class="bi bi-grid-1x2"></i> Dashboard
    </a>

    <div class="sb-label">Master Data</div>
    <a class="sb-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
        <i class="bi bi-tags"></i> Kategori
    </a>
    <a class="sb-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
        <i class="bi bi-box-seam"></i> Produk
    </a>
    <a class="sb-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
        <i class="bi bi-people"></i> Karyawan
    </a>

    <div class="sb-label">Transaksi</div>
    <a class="sb-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">
        <i class="bi bi-receipt"></i> Penjualan
    </a>
    <a class="sb-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
        <i class="bi bi-bar-chart-line"></i> Laporan
    </a>

    <div class="sb-footer">
        <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <div>
            <div class="sb-user-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="sb-user-role">Administrator</div>
        </div>
    </div>
</aside>

<header id="topbar">
    <span class="tb-title">@yield('page-title', 'Dashboard')</span>
    <div class="tb-right">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="tb-icon-btn" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
        <div class="tb-user">
            <div class="tb-uavatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <span class="tb-uname">{{ auth()->user()->name ?? 'User' }}</span>
        </div>
    </div>
</header>

<main id="content">

    @if(session('success'))
        <div class="flash flash-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
