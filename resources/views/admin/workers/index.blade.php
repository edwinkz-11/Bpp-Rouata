@extends('admin.layouts.app')

@section('title', 'Penyuluh Pertanian')

@section('content')
<div class="card-box">
    <div class="toolbar"><span class="text-muted small">Tampil di halaman publik Tentang BPP. Isi hanya data yang boleh dipublikasikan.</span>
        <a href="{{ route('admin.workers.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Penyuluh</a></div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:70px">Foto</th><th>Nama</th><th>Jabatan</th><th>Wilayah Binaan</th><th>Urutan</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($workers as $w)
                <tr>
                    <td>@if ($w->photo_url)<img src="{{ $w->photo_url }}" class="thumb rounded-circle" alt="">@else<span class="thumb rounded-circle d-inline-grid bg-light text-secondary" style="place-items:center"><i class="bi bi-person"></i></span>@endif</td>
                    <td class="fw-semibold">{{ $w->name }}</td><td>{{ $w->position ?: '-' }}</td><td>{{ $w->working_area ?: '-' }}</td><td>{{ $w->sort_order }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.workers.edit', $w) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        @can('manage-structure')
                        <form method="POST" action="{{ route('admin.workers.destroy', $w) }}" class="d-inline" data-confirm="Hapus data {{ $w->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">Belum ada data penyuluh.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $workers->links() }}
</div>
@endsection
