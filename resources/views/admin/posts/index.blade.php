@extends('admin.layouts.app')

@section('title', 'Berita BPP')

@section('content')
<div class="card-box">
    <div class="toolbar">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control w-auto" placeholder="Cari berita…" aria-label="Cari berita">
            <select name="status" class="form-select w-auto" aria-label="Status"><option value="">Semua status</option><option value="published" @selected(request('status') === 'published')>Terbit</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select>
            <button class="btn btn-outline-green">Filter</button>
        </form>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-green"><i class="bi bi-plus-lg"></i> Tulis Berita</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th style="width:70px">Foto</th><th>Judul</th><th>Tanggal terbit</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($posts as $p)
                <tr>
                    <td><img src="{{ $p->cover_url }}" class="thumb" alt=""></td>
                    <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($p->title, 70) }}</td>
                    <td class="small text-nowrap">{{ $p->published_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                    <td><x-status-badge :status="$p->status" /></td>
                    <td class="text-end text-nowrap">
                        @if ($p->status === 'published')<a href="{{ route('posts.show', $p->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" aria-label="Lihat {{ $p->title }}"><i class="bi bi-box-arrow-up-right"></i></a>@endif
                        <a href="{{ route('admin.posts.edit', $p) }}" class="btn btn-sm btn-outline-green" aria-label="Ubah {{ $p->title }}"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.posts.destroy', $p) }}" class="d-inline" data-confirm="Hapus berita {{ $p->title }}?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" aria-label="Hapus {{ $p->title }}"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">Belum ada berita. Klik “Tulis Berita” untuk mulai.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $posts->links() }}
</div>
@endsection
