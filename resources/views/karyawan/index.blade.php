@extends('layouts.app')

@section('title', 'Karyawan')
@section('page-title', 'Karyawan')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 style="font-size:20px; font-weight:700; margin:0;">Karyawan</h1>
    <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Karyawan
    </a>
</div>

<div class="card">
    @if($karyawans->isEmpty())
        <div class="text-center py-5" style="color:#9ca3af;">
            <i class="bi bi-people" style="font-size:38px; opacity:.35; display:block; margin-bottom:10px;"></i>
            <p style="font-size:13.5px; margin:0;">Belum ada karyawan. <a href="{{ route('karyawan.create') }}">Tambah sekarang</a></p>
        </div>
    @else
        <div class="table-responsive">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>No. Telepon</th>
                        <th>Tanggal Masuk</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($karyawans as $i => $k)
                    <tr>
                        <td style="color:#9ca3af; font-size:12px;">{{ $karyawans->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $k->nama_karyawan }}</td>
                        <td>
                            <span class="badge" style="background:#f0fdf4; color:#16a34a;">
                                {{ $k->jabatan }}
                            </span>
                        </td>
                        <td style="color:#6b7280;">{{ $k->no_telepon ?? '—' }}</td>
                        <td style="color:#6b7280;">
                            {{ $k->tanggal_masuk ? \Carbon\Carbon::parse($k->tanggal_masuk)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('karyawan.edit', $k->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus karyawan \'{{ $k->nama_karyawan }}\'?')">
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
                {{ $karyawans->links() }}
            </div>
        </div>
    @endif
</div>

@endsection
