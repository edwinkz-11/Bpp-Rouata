@extends('admin.layouts.app')

@section('title', 'Dokumen dan PPID')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari dokumen…" aria-label="Cari dokumen">
            <select name="category" class="form-select w-auto" aria-label="Kategori"><option value="">Semua kategori</option>@foreach (\App\Models\Document::CATEGORIES as $c)<option @selected(request('category') === $c)>{{ $c }}</option>@endforeach</select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
        <a href="{{ route('admin.documents.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tambah Dokumen</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Judul</th><th>Kategori</th><th>Tahun</th><th>File</th><th>Unduhan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($documents as $d)
                <tr>
                    <td class="fw-semibold">{{ $d->title }}</td>
                    <td class="small">{{ $d->category }}</td>
                    <td>{{ $d->year ?: '-' }}</td>
                    <td class="small text-nowrap">{{ strtoupper($d->file_ext) }}, {{ $d->size_label }}</td>
                    <td>{{ number_format($d->downloads, 0, ',', '.') }}</td>
                    <td>@if ($d->is_published)<span class="badge text-bg-success">Tampil</span>@else<span class="badge text-bg-secondary">Disembunyikan</span>@endif</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.documents.edit', $d) }}" class="btn btn-sm btn-outline-green" aria-label="Ubah {{ $d->title }}"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.documents.destroy', $d) }}" class="d-inline" data-confirm="Hapus dokumen {{ $d->title }}? File juga ikut terhapus.">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" aria-label="Hapus {{ $d->title }}"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-muted">Belum ada dokumen. Unggah laporan, programa, atau dokumen informasi publik yang sudah resmi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $documents->links() }}
</div>
@endsection
