@extends('layouts.public')

@section('title', 'Hasil Pencarian')

@push('head')
    <meta name="robots" content="noindex, follow">
@endpush

@section('content')
@include('partials.page-header', ['title' => 'Hasil Pencarian', 'subtitle' => $q !== '' ? 'Kata kunci: ' . $q : 'Cari berita, kegiatan, dokumen, dan komoditas.'])

<section class="section">
    <div class="container">
        <form method="GET" action="{{ route('search') }}" class="filter-bar mb-4" role="search">
            <label for="search-q" class="visually-hidden">Kata kunci</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input id="search-q" type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Minimal 3 huruf…" maxlength="100">
                <button class="btn btn-green">Cari</button>
            </div>
        </form>

        @if ($tooShort)
            <div class="alert alert-warning">Kata kunci terlalu pendek. Gunakan minimal 3 huruf.</div>
        @elseif ($q !== '' && $total === 0)
            <x-empty icon="bi-search" title="Tidak ada hasil" text="Coba kata kunci lain yang lebih umum." />
        @elseif ($q !== '')
            <p class="text-muted" role="status">{{ $total }} hasil ditemukan.</p>

            @if ($results['posts']->isNotEmpty())
                <h2 class="h5 fw-bold mt-4 mb-3">Berita</h2>
                <div class="list-group mb-3">
                    @foreach ($results['posts'] as $p)
                        <a class="list-group-item list-group-item-action" href="{{ route('posts.show', $p->slug) }}"><span class="fw-semibold d-block">{{ $p->title }}</span><small class="text-muted">{{ $p->summary }}</small></a>
                    @endforeach
                </div>
            @endif
            @if ($results['activities']->isNotEmpty())
                <h2 class="h5 fw-bold mt-4 mb-3">Kegiatan Pertanian</h2>
                <div class="list-group mb-3">
                    @foreach ($results['activities'] as $a)
                        <a class="list-group-item list-group-item-action" href="{{ route('activities.show', $a->slug) }}"><span class="fw-semibold d-block">{{ $a->title }}</span><small class="text-muted">{{ $a->activity_date?->translatedFormat('d F Y') }} {{ $a->location_name ? '· ' . $a->location_name : '' }}</small></a>
                    @endforeach
                </div>
            @endif
            @if ($results['documents']->isNotEmpty())
                <h2 class="h5 fw-bold mt-4 mb-3">Dokumen</h2>
                <div class="list-group mb-3">
                    @foreach ($results['documents'] as $d)
                        <a class="list-group-item list-group-item-action" href="{{ route('documents.download', $d) }}"><span class="fw-semibold d-block">{{ $d->title }}</span><small class="text-muted">{{ $d->category }} · {{ strtoupper($d->file_ext) }}, {{ $d->size_label }}</small></a>
                    @endforeach
                </div>
            @endif
            @if ($results['commodities']->isNotEmpty())
                <h2 class="h5 fw-bold mt-4 mb-3">Potensi Pertanian</h2>
                <div class="list-group mb-3">
                    @foreach ($results['commodities'] as $c)
                        <a class="list-group-item list-group-item-action" href="{{ route('commodities.index') }}"><span class="fw-semibold d-block">{{ $c->name }}</span><small class="text-muted">{{ $c->category }}</small></a>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
