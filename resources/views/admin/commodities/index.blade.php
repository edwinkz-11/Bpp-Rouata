@extends('admin.layouts.app')

@section('title', 'Potensi Pertanian')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari komoditas…">
            <select name="category" class="form-select w-auto"><option value="">Semua kategori</option>@foreach (\App\Models\Commodity::CATEGORIES as $c)<option @selected(request('category') === $c)>{{ $c }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
        <a href="{{ route('admin.commodities.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Komoditas</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:70px">Foto</th><th>Nama</th><th>Kategori</th><th>Lokasi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($commodities as $c)
                <tr>
                    <td><img src="{{ $c->image_url }}" class="thumb" alt=""></td>
                    <td class="fw-semibold">{{ $c->name }}</td><td>{{ $c->category }}</td><td class="small">{{ $c->location_text ?: '-' }}</td>
                    <td><x-status-badge :status="$c->status" /></td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.commodities.edit', $c) }}" class="btn btn-sm btn-outline-green"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.commodities.destroy', $c) }}" class="d-inline" data-confirm="Hapus komoditas {{ $c->name }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-muted">Belum ada komoditas. Tambahkan hanya komoditas yang benar-benar dikembangkan di Routa.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $commodities->links() }}
</div>
@endsection
