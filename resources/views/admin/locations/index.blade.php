@extends('admin.layouts.app')

@section('title', 'Peta Wilayah — Titik Lokasi')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari lokasi…">
            <select name="category" class="form-select w-auto"><option value="">Semua kategori</option>@foreach (\App\Models\Location::CATEGORIES as $k => $c)<option value="{{ $k }}" @selected(request('category') === $k)>{{ $c['label'] }}</option>@endforeach</select>
            <select name="status" class="form-select w-auto"><option value="">Semua status</option>@foreach (\App\Models\Location::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
        <a href="{{ route('admin.locations.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Titik</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Kategori</th><th>Desa</th><th>Koordinat</th><th>Verifikasi</th><th>Tayang</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($locations as $l)
                <tr>
                    <td class="fw-semibold">{{ $l->name }}</td>
                    <td><span class="badge" style="background: {{ \App\Models\Location::CATEGORIES[$l->category]['color'] ?? '#64748B' }}">{{ $l->category_label }}</span></td>
                    <td>{{ $l->village?->name ?? '-' }}</td>
                    <td class="small text-nowrap">{{ number_format($l->latitude, 6) }}, {{ number_format($l->longitude, 6) }}</td>
                    <td><x-status-badge :status="$l->verification_status" /></td>
                    <td>{!! $l->is_published ? '<span class="badge text-bg-success">Tampil</span>' : '<span class="badge text-bg-secondary">Disembunyikan</span>' !!}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.locations.edit', $l) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        @can('delete-record', $l)
                        <form method="POST" action="{{ route('admin.locations.destroy', $l) }}" class="d-inline" data-confirm="Hapus lokasi {{ $l->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-muted">Belum ada titik lokasi. Titik ditambahkan dari koordinat GPS yang sebenarnya.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $locations->links() }}
</div>
@endsection
