@extends('admin.layouts.app')

@section('title', 'Data Desa')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari desa…"><button class="btn btn-outline-green">Cari</button></form>
        <a href="{{ route('admin.villages.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Desa</a>
    </div>
    @if ($villages->isEmpty())
        <p class="text-muted mb-0">Belum ada data desa. Isi hanya nama desa yang sudah pasti benar.</p>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:70px">Foto</th><th>Nama Desa</th><th>Koordinat</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @foreach ($villages as $v)
                <tr>
                    <td><img src="{{ $v->image_url }}" class="thumb" alt=""></td>
                    <td class="fw-semibold">{{ $v->name }}<div class="small text-muted fw-normal">{{ \Illuminate\Support\Str::limit($v->description, 70) }}</div></td>
                    <td class="small">{{ $v->latitude !== null ? $v->latitude . ', ' . $v->longitude : 'Belum diisi' }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.villages.edit', $v) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        @can('manage-structure')
                        <form method="POST" action="{{ route('admin.villages.destroy', $v) }}" class="d-inline" data-confirm="Hapus desa {{ $v->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $villages->links() }}
    @endif
</div>
@endsection
