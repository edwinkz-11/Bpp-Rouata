<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        // Nilai dari @section('x', '...') sudah di-escape oleh Blade, jadi dicetak apa adanya. Nilai lain di-escape manual.
        $metaTitle = trim($__env->yieldContent('title', 'Beranda')) . ' | ' . e($bpp->name);
        $metaDesc = trim($__env->yieldContent('description')) ?: 'Pusat informasi pertanian Kecamatan Routa: kegiatan penyuluhan, potensi pertanian, dan peta wilayah.';
        $metaImage = trim($__env->yieldContent('og_image')) ?: e($bpp->logo_url);
    @endphp
    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDesc !!}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $bpp->name }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="{{ trim($__env->yieldContent('og_type')) ?: 'website' }}">
    <meta property="og:title" content="{!! $metaTitle !!}">
    <meta property="og:description" content="{!! $metaDesc !!}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{!! $metaImage !!}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDesc !!}">
    <meta name="twitter:image" content="{!! $metaImage !!}">
    <link rel="icon" href="{{ $bpp->logo_url }}">
    <link href="{{ asset('vendor/poppins/poppins.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    @stack('head')
    @stack('styles')
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>
<body>
<a class="skip-link" href="#konten">Lewati ke konten utama</a>
@php
    $nav = [
        ['home', 'Home', 'bi-house-door-fill', 'home'],
        ['district', 'Profil Kecamatan', 'bi-bank2', 'district'],
        ['activities.index', 'Kegiatan Pertanian', 'bi-camera-fill', 'activities.*'],
        ['commodities.index', 'Potensi Pertanian', 'bi-flower1', 'commodities.*'],
        ['news', 'Berita', 'bi-newspaper', 'news'],
        ['map', 'Peta Wilayah', 'bi-geo-alt-fill', 'map'],
        ['about', 'Tentang BPP', 'bi-info-circle-fill', 'about'],
    ];
    $publicInfoActive = request()->routeIs('documents.*', 'ppid', 'contact');
@endphp

<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-xxl" aria-label="Navigasi utama">
        <div class="container nav-wide">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ $bpp->logo_url }}" alt="Logo BPP" width="46" height="46">
                <span class="lh-sm">
                    <strong class="d-block brand-title">{{ $bpp->name }}</strong>
                    <small class="brand-sub">{{ $bpp->institution_name }}</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <i class="bi bi-list fs-2"></i>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="mainNav">
                <ul class="navbar-nav gap-xxl-1 mt-3 mt-xxl-0">
                    @foreach ($nav as [$route, $label, $icon, $pattern])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}" @if (request()->routeIs($pattern)) aria-current="page" @endif>{{ $label }}</a>
                        </li>
                        @if ($route === 'news')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ $publicInfoActive ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Informasi Publik</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('documents.index') }}">Dokumen dan Unduhan</a></li>
                                    <li><a class="dropdown-item" href="{{ route('ppid') }}">PPID</a></li>
                                    <li><a class="dropdown-item" href="{{ route('contact') }}">Kontak dan Pengaduan</a></li>
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <form class="nav-search ms-xxl-2 mt-3 mt-xxl-0" action="{{ route('search') }}" method="GET" role="search">
                    <label for="nav-q" class="visually-hidden">Cari di situs</label>
                    <input id="nav-q" type="search" name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}" class="form-control form-control-sm" placeholder="Cari…" minlength="3" maxlength="100">
                    <button class="btn btn-sm btn-green" aria-label="Cari"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </nav>
</header>

<main id="konten" tabindex="-1">@yield('content')</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4 py-5">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $bpp->logo_url }}" alt="Logo BPP" width="56" height="56">
                    <div class="lh-sm">
                        <strong class="d-block">{{ $bpp->name }}</strong>
                        <small class="opacity-75">{{ $bpp->institution_name }}</small>
                    </div>
                </div>
                <p class="small opacity-75 mb-0">{{ $site['slogan'] ?? 'Membangun Pertanian Routa yang Maju, Mandiri, dan Berkelanjutan.' }}</p>
            </div>
            <div class="col-md-6 col-lg-4">
                <h6 class="footer-title">Kontak Kantor</h6>
                <ul class="list-unstyled small mb-0 footer-contact">
                    <li><i class="bi bi-geo-alt-fill"></i><span>{{ $bpp->address ?: 'Alamat kantor belum diisi' }}</span></li>
                    <li><i class="bi bi-telephone-fill"></i><span>{{ $bpp->phone ?: 'Nomor telepon belum diisi' }}</span></li>
                    <li><i class="bi bi-envelope-fill"></i><span>{{ $bpp->email ?: 'Email belum diisi' }}</span></li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-4">
                <h6 class="footer-title">Ikuti Kami</h6>
                <div class="d-flex gap-2 mb-3">
                    @foreach ([['instagram_url', 'bi-instagram', 'Instagram'], ['facebook_url', 'bi-facebook', 'Facebook'], ['youtube_url', 'bi-youtube', 'YouTube'], ['tiktok_url', 'bi-tiktok', 'TikTok']] as [$key, $icon, $label])
                        @if (! empty($site[$key]))
                            <a class="social-btn" href="{{ $site[$key] }}" target="_blank" rel="noopener" aria-label="{{ $label }}"><i class="bi {{ $icon }}"></i></a>
                        @endif
                    @endforeach
                </div>
                <div class="small d-flex flex-wrap gap-3">
                    <a href="{{ route('gallery') }}">Galeri</a>
                    <a href="{{ route('activities.index') }}">Kegiatan</a>
                    <a href="{{ route('documents.index') }}">Dokumen</a>
                    <a href="{{ route('ppid') }}">PPID</a>
                    <a href="{{ route('contact') }}">Kontak dan Pengaduan</a>
                    <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
                    <a href="{{ route('admin.login') }}">Masuk Petugas</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom small">
            {{ $site['footer_text'] ?? '© ' . date('Y') . ' ' . $bpp->name . '. Hak cipta dilindungi.' }}
        </div>
    </div>
</footer>

{{-- Lightbox foto --}}
<div class="modal fade" id="lightbox" tabindex="-1" aria-label="Pratinjau foto" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white lb-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <button type="button" class="lb-nav lb-prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <img id="lb-img" src="" alt="Foto dokumentasi" class="img-fluid">
                <button type="button" class="lb-nav lb-next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                <div class="lb-caption" id="lb-caption"></div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
<script src="{{ asset('js/site.js') }}"></script>
</body>
</html>
