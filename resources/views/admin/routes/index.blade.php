@extends('admin.layouts.app')

@section('title', 'Data Jalur')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari jalur…">
            <select name="status" class="form-select w-auto"><option value="">Semua status</option>@foreach (\App\Models\Location::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
        <a href="{{ route('admin.routes.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Jalur</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama Jalur</th><th>Dari → Ke</th><th>Titik</th><th>Verifikasi</th><th>Tayang</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($routes as $r)
                <tr>
                    <td class="fw-semibold">{{ $r->name }}</td>
                    <td class="small">{{ $r->startLocation?->name ?? '-' }} → {{ $r->endLocation?->name ?? '-' }}</td>
                    <td>{{ $r->points_count }}</td>
                    <td><x-status-badge :status="$r->verification_status" /></td>
                    <td>{!! $r->is_published ? '<span class="badge text-bg-success">Tampil</span>' : '<span class="badge text-bg-secondary">Disembunyikan</span>' !!}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.routes.edit', $r) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        @can('delete-record', $r)
                        <form method="POST" action="{{ route('admin.routes.destroy', $r) }}" class="d-inline" data-confirm="Hapus jalur {{ $r->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">Belum ada jalur. Jalur digambar dari titik GPS hasil survei lapangan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $routes->links() }}
</div>
@endsection
