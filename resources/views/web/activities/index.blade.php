@extends('layouts.public')

@section('title', 'Kegiatan Pertanian')

@section('content')
@include('partials.page-header', ['title' => 'Kegiatan Pertanian', 'subtitle' => 'Dokumentasi penyuluhan, pelatihan, tanam, panen, dan kegiatan lapangan lainnya.'])

<section class="section">
    <div class="container">
        <form method="GET" action="{{ route('activities.index') }}" class="filter-bar mb-4">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Cari kegiatan, lokasi, atau kata kunci…">
                @if ($activeCategory)<input type="hidden" name="kategori" value="{{ $activeCategory }}">@endif
                <button class="btn btn-green">Cari</button>
            </div>
        </form>

        <div class="chip-row mb-4">
            <a href="{{ route('activities.index', array_filter(['q' => $search])) }}" class="chip {{ ! $activeCategory ? 'active' : '' }}">Semua</a>
            @foreach ($categories as $cat)
                <a href="{{ route('activities.index', array_filter(['kategori' => $cat->id, 'q' => $search])) }}" class="chip {{ $activeCategory === $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if ($activities->isEmpty())
            <x-empty icon="bi-search" title="Kegiatan tidak ditemukan" text="Coba kata kunci atau kategori lain, atau kegiatan belum ditambahkan petugas.">
                <a href="{{ route('activities.index') }}" class="btn btn-outline-green btn-sm mt-2">Tampilkan semua</a>
            </x-empty>
        @else
            <div class="row g-4">
                @foreach ($activities as $activity)
                    <div class="col-md-6 col-lg-4">@include('partials.activity-card', ['activity' => $activity])</div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $activities->links() }}</div>
        @endif
    </div>
</section>
@endsection
