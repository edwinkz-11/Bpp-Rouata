@extends('layouts.public')

@section('title', 'Dokumen dan Unduhan')
@section('description', 'Unduh laporan kinerja, programa penyuluhan, peraturan, formulir, dan dokumen informasi publik BPP Pertanian Routa.')

@section('content')
@include('partials.page-header', ['title' => 'Dokumen dan Unduhan', 'subtitle' => 'Laporan, programa penyuluhan, peraturan, formulir, dan dokumen informasi publik.'])

<section class="section">
    <div class="container">
        <form method="GET" action="{{ route('documents.index') }}" class="filter-bar mb-4">
            <div class="row g-2">
                <div class="col-lg-6">
                    <label for="doc-q" class="visually-hidden">Kata kunci</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input id="doc-q" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Cari judul atau isi dokumen…">
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <label for="doc-cat" class="visually-hidden">Kategori</label>
                    <select id="doc-cat" name="kategori" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $c)<option @selected(request('kategori') === $c)>{{ $c }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <label for="doc-year" class="visually-hidden">Tahun</label>
                    <select id="doc-year" name="tahun" class="form-select">
                        <option value="">Semua tahun</option>
                        @foreach ($years as $y)<option value="{{ $y }}" @selected((string) request('tahun') === (string) $y)>{{ $y }}</option>@endforeach
                    </select>
                </div>
                <div class="col-lg-1 d-grid"><button class="btn btn-green">Cari</button></div>
            </div>
        </form>

        @if ($documents->isEmpty())
            <x-empty icon="bi-folder2-open" title="Dokumen tidak ditemukan" text="Coba kata kunci lain, atau dokumen belum diunggah petugas.">
                <a href="{{ route('documents.index') }}" class="btn btn-outline-green btn-sm mt-2">Tampilkan semua</a>
            </x-empty>
        @else
            <div class="list-group shadow-soft">
                @foreach ($documents as $d)
                    <div class="list-group-item d-flex flex-wrap align-items-center gap-3 py-3">
                        <i class="bi bi-file-earmark-text fs-3 text-green" aria-hidden="true"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $d->title }}</div>
                            <div class="small text-muted">
                                {{ $d->category }}@if ($d->year) &middot; {{ $d->year }}@endif
                                &middot; {{ strtoupper($d->file_ext) }}, {{ $d->size_label }}
                                &middot; {{ number_format($d->downloads, 0, ',', '.') }} unduhan
                            </div>
                            @if ($d->description)<div class="small text-muted mt-1">{{ $d->description }}</div>@endif
                        </div>
                        <a href="{{ route('documents.download', $d) }}" class="btn btn-outline-green btn-sm" aria-label="Unduh {{ $d->title }}"><i class="bi bi-download me-1"></i> Unduh</a>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex justify-content-center">{{ $documents->links() }}</div>
        @endif
    </div>
</section>
@endsection
