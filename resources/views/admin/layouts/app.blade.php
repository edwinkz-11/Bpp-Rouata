<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') | Admin {{ $bpp->name }}</title>
    <link rel="icon" href="{{ $bpp->logo_url }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
@php
    $menu = [
        ['Dashboard', 'bi-speedometer2', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
        ['Profil BPP', 'bi-building', route('admin.profile.edit', 'bpp'), request()->is('admin/profil/bpp')],
        ['Profil Kecamatan', 'bi-bank2', route('admin.profile.edit', 'kecamatan'), request()->is('admin/profil/kecamatan')],
        ['Data Desa', 'bi-houses', route('admin.villages.index'), request()->routeIs('admin.villages.*')],
        ['Kegiatan Pertanian', 'bi-calendar-event', route('admin.activities.index'), request()->routeIs('admin.activities.*')],
        ['Kategori Kegiatan', 'bi-tags', route('admin.categories.index'), request()->routeIs('admin.categories.*')],
        ['Galeri Dokumentasi', 'bi-images', route('admin.gallery.index'), request()->routeIs('admin.gallery.*')],
        ['Potensi Pertanian', 'bi-flower2', route('admin.commodities.index'), request()->routeIs('admin.commodities.*')],
        ['Peta Wilayah', 'bi-geo-alt', route('admin.locations.index'), request()->routeIs('admin.locations.*')],
        ['Data Jalur', 'bi-signpost-split', route('admin.routes.index'), request()->routeIs('admin.routes.*')],
        ['Penyuluh', 'bi-person-badge', route('admin.workers.index'), request()->routeIs('admin.workers.*')],
        ['Pengaturan Website', 'bi-gear', route('admin.settings.edit'), request()->routeIs('admin.settings.*')],
    ];
    if (auth()->user()->isAdmin()) {
        $menu[] = ['Manajemen Pengguna', 'bi-people', route('admin.users.index'), request()->routeIs('admin.users.*')];
    }
@endphp

<div class="admin-wrap">
    <aside class="admin-sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="side-brand">
            <img src="{{ $bpp->logo_url }}" alt="Logo" width="40" height="40">
            <span><strong>Admin BPP Routa</strong><small>Panel pengelola</small></span>
        </a>
        <nav class="side-nav">
            @foreach ($menu as [$label, $icon, $url, $isActive])
                <a href="{{ $url }}" class="{{ $isActive ? 'active' : '' }}"><i class="bi {{ $icon }}"></i>{{ $label }}</a>
            @endforeach
        </nav>
        <a href="{{ route('home') }}" target="_blank" class="side-site"><i class="bi bi-box-arrow-up-right"></i> Lihat website</a>
    </aside>
    <div class="side-backdrop" id="sideBackdrop"></div>

    <div class="admin-main">
        <header class="admin-top">
            <button class="btn btn-light d-lg-none" id="sideToggle" aria-label="Menu"><i class="bi bi-list fs-5"></i></button>
            <h1 class="admin-title">@yield('title', 'Dashboard')</h1>
            <div class="ms-auto dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    <x-status-badge :status="auth()->user()->role" />
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.account.edit') }}"><i class="bi bi-person-gear me-2"></i>Akun Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">@csrf
                            <button class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="admin-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if ($errors->any() && ! $errors->has('email'))
                <div class="alert alert-danger"><strong>Data belum bisa disimpan.</strong> Periksa isian yang bertanda merah di bawah.</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
