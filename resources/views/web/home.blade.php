@extends('layouts.public')

@section('title', 'Beranda')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
@endpush

@push('head')
<script type="application/ld+json">{!! json_encode(array_filter([
    '@@context' => 'https://schema.org',
    '@@type' => 'GovernmentOrganization',
    'name' => $bpp->institution_name ?: $bpp->name,
    'alternateName' => $bpp->name,
    'url' => url('/'),
    'logo' => $bpp->logo_url,
    'telephone' => $bpp->phone ?: null,
    'email' => $bpp->email ?: null,
    'address' => $bpp->address ? ['@@type' => 'PostalAddress', 'streetAddress' => $bpp->address, 'addressCountry' => 'ID'] : null,
    'sameAs' => array_values(array_filter([$site['instagram_url'] ?? null, $site['facebook_url'] ?? null, $site['youtube_url'] ?? null, $site['tiktok_url'] ?? null])) ?: null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endpush

@section('content')
@php
    $heroUrl = \App\Services\ImageService::url($site['hero_image'] ?? null, 'images/routa-udara-2.jpg');
@endphp

{{-- A. HERO --}}
<section class="hero" style="background-image: url('{{ $heroUrl }}')">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <span class="hero-badge">Selamat Datang di</span>
        <h1 class="hero-title">BPP PERTANIAN ROUTA</h1>
        <p class="hero-sub">Balai Penyuluhan Pertanian Kecamatan Routa</p>
        <p class="hero-slogan">“{{ $site['slogan'] ?? 'Membangun Pertanian Routa yang Maju, Mandiri, dan Berkelanjutan.' }}”</p>
        <a href="#sekilas" class="btn btn-accent btn-lg">Kenali Lebih Lanjut <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
</section>

{{-- B. SEKILAS TENTANG ROUTA --}}
<section id="sekilas" class="section">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="section-label"><i class="bi bi-flower1"></i> Sekilas Tentang Routa</div>
                <h2 class="section-title">{{ $district->name }}</h2>
                @if ($district->description)
                    <p class="text-muted">{{ \Illuminate\Support\Str::limit($district->description, 520) }}</p>
                @else
                    <p class="placeholder-note"><i class="bi bi-pencil-square"></i> Perkenalan singkat Kecamatan Routa belum diisi. Petugas dapat mengisinya di Dashboard Admin → Profil Kecamatan.</p>
                @endif
                <a href="{{ route('district') }}" class="btn btn-green">Profil Kecamatan <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
            <div class="col-lg-6">
                <img src="{{ $district->cover_url }}" class="img-fluid rounded-4 shadow-soft w-100 cover-img" alt="Wilayah {{ $district->name }}">
            </div>
        </div>
    </div>
</section>

{{-- Profil BPP singkat --}}
<section class="section-band">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-lg-9">
                <h2 class="h4 fw-bold mb-1"><i class="bi bi-patch-check-fill text-green me-1"></i> Tentang {{ $bpp->name }}</h2>
                <p class="mb-0 text-muted">
                    {{ $bpp->description ? \Illuminate\Support\Str::limit($bpp->description, 260) : 'Profil BPP belum diisi. Petugas dapat mengisinya di Dashboard Admin → Profil BPP.' }}
                </p>
            </div>
            <div class="col-lg-3 text-lg-end"><a href="{{ route('about') }}" class="btn btn-outline-green">Selengkapnya</a></div>
        </div>
    </div>
</section>

{{-- C. KEGIATAN TERBARU --}}
<section class="section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <div class="section-label"><i class="bi bi-megaphone-fill"></i> Kegiatan Terbaru</div>
                <h2 class="section-title mb-0">Dokumentasi Kegiatan Pertanian</h2>
            </div>
            <a href="{{ route('activities.index') }}" class="btn btn-outline-green">Lihat Semua Kegiatan</a>
        </div>
        @if ($activities->isEmpty())
            <x-empty icon="bi-camera" title="Belum ada kegiatan yang diterbitkan" text="Kegiatan akan tampil di sini setelah petugas menambahkannya." />
        @else
            <div class="row g-4">
                @foreach ($activities as $activity)
                    <div class="col-md-6 col-lg-4">@include('partials.activity-card', ['activity' => $activity])</div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- D. POTENSI PERTANIAN --}}
<section class="section potensi-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <div class="section-label"><i class="bi bi-flower2"></i> Potensi Pertanian</div>
                <h2 class="section-title mb-0">Komoditas Kecamatan Routa</h2>
            </div>
            <a href="{{ route('commodities.index') }}" class="btn btn-outline-green">Lihat Semua Potensi</a>
        </div>
        @if ($commodities->isEmpty())
            <x-empty icon="bi-flower3" title="Data komoditas belum diisi" text="Komoditas ditambahkan oleh petugas melalui Dashboard Admin → Potensi Pertanian." />
        @else
            <div class="potensi-grid">
            @foreach ($commodities as $c)
                <a href="{{ route('commodities.index', ['kategori' => $c->category]) }}" class="potensi-tile">
                    @if ($c->image)
                        <img src="{{ $c->image_url }}" alt="{{ $c->name }}" loading="lazy">
                    @else
                        <i class="bi bi-flower1 potensi-icon"></i>
                    @endif
                    <span class="potensi-chip">{{ $c->category }}</span>
                    <div class="potensi-info"><strong>{{ $c->name }}</strong><i class="bi bi-arrow-right-circle"></i></div>
                </a>
            @endforeach
            </div>
            @if ($commodities->contains(fn ($c) => $c->image))
                <p class="small text-muted mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Sebagian foto adalah foto ilustrasi (Wikimedia Commons) dan akan diganti foto lapangan Routa.</p>
            @endif
        @endif
    </div>
</section>

{{-- E. PETA WILAYAH --}}
<section class="section">
    <div class="container">
        <div class="section-label"><i class="bi bi-geo-alt-fill"></i> Peta Wilayah</div>
        <h2 class="section-title">Peta Wilayah Kecamatan Routa</h2>
        <div class="row g-0 map-preview shadow-soft">
            <div class="col-lg-8">
                <div id="map" class="map-box map-mini" data-config='@json($mapConfig)'></div>
            </div>
            <div class="col-lg-4 map-side">
                <h3 class="h5 fw-bold">Informasi Peta</h3>
                <p class="text-muted small">Peta ini menampilkan lokasi kantor BPP, desa, kelompok tani, dan lahan pertanian yang sudah dipetakan petugas.</p>
                @unless ($hasLocations)
                    <p class="placeholder-note small"><i class="bi bi-info-circle"></i> Belum ada titik lokasi yang diterbitkan. Titik ditambahkan bertahap oleh petugas.</p>
                @endunless
                <a href="{{ route('map') }}" class="btn btn-green mt-auto">Lihat Peta Lengkap <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- F. GALERI --}}
@if ($posts->isNotEmpty())
<section class="section" id="berita-bpp">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <div class="section-label"><i class="bi bi-megaphone"></i> Dari BPP Routa</div>
                <h2 class="section-title mb-0">Berita Terbaru BPP</h2>
            </div>
            <a href="{{ route('news') }}" class="btn btn-outline-green">Semua Berita</a>
        </div>
        <div class="row g-4">
            @foreach ($posts as $post)
                <div class="col-md-6 col-lg-4">@include('partials.post-card', ['post' => $post])</div>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.kementan-news', ['news' => $news, 'compact' => true])

<section class="section pt-0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <div class="section-label"><i class="bi bi-images"></i> Galeri Dokumentasi</div>
                <h2 class="section-title mb-0">Momen di Lapangan</h2>
            </div>
            <a href="{{ route('gallery') }}" class="btn btn-outline-green">Lihat Galeri Lengkap</a>
        </div>
        @if ($photos->isEmpty())
            <x-empty icon="bi-image" title="Belum ada foto dokumentasi" text="Foto kegiatan akan tampil di sini setelah diunggah petugas." />
        @else
            <div class="row g-3">
                @foreach ($photos as $photo)
                    <div class="col-6 col-md-4">
                        <a href="{{ $photo->url }}" class="gallery-item" data-lightbox="home" data-caption="{{ $photo->caption ?: $photo->activity?->title }}">
                            <img src="{{ $photo->url }}" alt="{{ $photo->caption ?: 'Dokumentasi kegiatan' }}" loading="lazy">
                            <span class="zoom"><i class="bi bi-zoom-in"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/public-map.js') }}"></script>
@endpush
