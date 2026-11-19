@extends('layouts.public')

@section('title', $activity->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $activity->description), 150))

@section('content')
@include('partials.page-header', ['title' => $activity->title, 'parent' => ['Kegiatan Pertanian', route('activities.index')]])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                @if ($activity->has_image)
                    <a href="{{ $activity->cover_url }}" data-lightbox="detail" data-caption="{{ $activity->title }}" class="d-block mb-4">
                        <img src="{{ $activity->cover_url }}" class="img-fluid rounded-4 shadow-soft w-100 cover-img" alt="{{ $activity->title }}">
                    </a>
                @endif

                <div class="info-card mb-4">
                    <h2 class="h5 fw-bold">Deskripsi Kegiatan</h2>
                    @if ($activity->description)
                        <div class="text-muted prose">{!! nl2br(e($activity->description)) !!}</div>
                    @else
                        <p class="placeholder-note mb-0">Deskripsi belum diisi.</p>
                    @endif
                </div>

                @if ($activity->photos->isNotEmpty())
                    <h2 class="h5 fw-bold mb-3"><i class="bi bi-images text-green me-2"></i>Foto Dokumentasi ({{ $activity->photos->count() }})</h2>
                    <div class="row g-3">
                        @foreach ($activity->photos as $photo)
                            <div class="col-6 col-md-4">
                                <a href="{{ $photo->url }}" class="gallery-item" data-lightbox="detail" data-caption="{{ $photo->caption ?: $activity->title }}">
                                    <img src="{{ $photo->url }}" alt="{{ $photo->caption ?: $activity->title }}" loading="lazy">
                                    <span class="zoom"><i class="bi bi-zoom-in"></i></span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="col-lg-4">
                <div class="info-card">
                    <h2 class="h5 fw-bold mb-3">Informasi Kegiatan</h2>
                    <ul class="list-unstyled meta-list mb-0">
                        <li><i class="bi bi-tag-fill"></i> <span>{{ $activity->category?->name ?? 'Tanpa kategori' }}</span></li>
                        <li><i class="bi bi-calendar3"></i> <span>{{ $activity->activity_date?->translatedFormat('l, d F Y') ?? 'Tanggal belum diisi' }}</span></li>
                        <li><i class="bi bi-geo-alt-fill"></i> <span>{{ $activity->location_name ?: 'Lokasi belum diisi' }}@if ($activity->village) ({{ $activity->village->name }})@endif</span></li>
                    </ul>
                </div>
                <a href="{{ route('activities.index') }}" class="btn btn-outline-green w-100 mt-3"><i class="bi bi-arrow-left me-1"></i> Kembali ke daftar kegiatan</a>
            </aside>
        </div>

        @if ($related->isNotEmpty())
            <h2 class="section-title mt-5">Kegiatan Lainnya</h2>
            <div class="row g-4">
                @foreach ($related as $r)
                    <div class="col-md-6 col-lg-4">@include('partials.activity-card', ['activity' => $r])</div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
