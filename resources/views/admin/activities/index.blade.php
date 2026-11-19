@extends('admin.layouts.app')

@section('title', 'Kegiatan Pertanian')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari judul…">
            <select name="category" class="form-select w-auto"><option value="">Semua kategori</option>@foreach ($categories as $id => $name)<option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>@endforeach</select>
            <select name="status" class="form-select w-auto"><option value="">Semua status</option><option value="published" @selected(request('status') === 'published')>Terbit</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
        <a href="{{ route('admin.activities.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Kegiatan</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:80px">Foto</th><th>Kegiatan</th><th>Kategori</th><th>Tanggal</th><th>Foto</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($activities as $a)
                <tr>
                    <td><img src="{{ $a->cover_url }}" class="thumb" alt=""></td>
                    <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($a->title, 55) }}<div class="small text-muted fw-normal">{{ $a->location_name }}</div></td>
                    <td>{{ $a->category?->name ?? '-' }}</td>
                    <td class="text-nowrap">{{ $a->activity_date?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>{{ $a->photos_count }}</td>
                    <td><x-status-badge :status="$a->status" /></td>
                    <td class="text-end text-nowrap">
                        @if ($a->status === 'published')<a href="{{ route('activities.show', $a->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat"><i class="bi bi-eye"></i></a>@endif
                        <a href="{{ route('admin.activities.edit', $a) }}" class="btn btn-sm btn-outline-green" title="Edit"><i class="bi bi-pencil"></i></a>
                        @can('delete-record', $a)
                        <form method="POST" action="{{ route('admin.activities.destroy', $a) }}" class="d-inline" data-confirm="Hapus kegiatan ini beserta seluruh fotonya?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-muted">Belum ada kegiatan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $activities->links() }}
</div>
@endsection
