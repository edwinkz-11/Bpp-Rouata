@extends('admin.layouts.app')

@section('title', 'Kategori Kegiatan')

@section('content')
<div class="card-box">
    <div class="toolbar"><span class="text-muted small">Kategori dipakai untuk filter di halaman Kegiatan Pertanian.</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Kategori</a></div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Jumlah Kegiatan</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($categories as $c)
                <tr>
                    <td class="fw-semibold">{{ $c->name }}<div class="small text-muted fw-normal">{{ $c->description }}</div></td>
                    <td>{{ $c->activities_count }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        @can('manage-structure')
                        <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="d-inline" data-confirm="Hapus kategori {{ $c->name }}? Kegiatan di dalamnya menjadi tanpa kategori.">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-muted">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $categories->links() }}
</div>
@endsection
