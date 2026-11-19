<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') | {{ $bpp->name }}</title>
    <meta name="description" content="@yield('description', 'Pusat informasi pertanian Kecamatan Routa: kegiatan penyuluhan, potensi pertanian, dan peta wilayah.')">
    <link rel="icon" href="{{ $bpp->logo_url }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
</head>
<body>
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
@endphp

<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-xl">
        <div class="container">
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
                <ul class="navbar-nav gap-xl-1 mt-3 mt-xl-0">
                    @foreach ($nav as [$route, $label, $icon, $pattern])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}">{{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>

<main>@yield('content')</main>

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
                    <a href="{{ route('map') }}">Peta</a>
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
<div class="modal fade" id="lightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white lb-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                <button type="button" class="lb-nav lb-prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <img id="lb-img" src="" alt="" class="img-fluid">
                <button type="button" class="lb-nav lb-next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                <div class="lb-caption" id="lb-caption"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script src="{{ asset('js/site.js') }}"></script>
</body>
</html>
