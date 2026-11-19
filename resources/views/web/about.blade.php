@extends('layouts.public')

@section('title', 'Tentang BPP')

@section('content')
@include('partials.page-header', ['title' => 'Tentang BPP', 'subtitle' => $bpp->institution_name])

@php
    $blocks = [
        ['Profil BPP', 'bi-building', $bpp->description],
        ['Visi', 'bi-eye', $bpp->vision],
        ['Misi', 'bi-bullseye', $bpp->mission],
        ['Tugas dan Fungsi', 'bi-list-check', $bpp->tasks_functions],
    ];
    $mapsQuery = config('routa.maps_query', 'Kecamatan Routa, Kabupaten Konawe, Sulawesi Tenggara');
@endphp

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ $bpp->logo_url }}" alt="Logo BPP" width="84" height="84">
                    <div>
                        <h2 class="h4 fw-bold mb-0">{{ $bpp->name }}</h2>
                        <span class="text-muted">{{ $bpp->institution_name }}</span>
                    </div>
                </div>

                @foreach ($blocks as [$title, $icon, $text])
                    <div class="info-card mb-3">
                        <h3 class="h5 fw-bold"><i class="bi {{ $icon }} text-green me-2"></i>{{ $title }}</h3>
                        @if ($text)
                            <div class="text-muted prose">{!! nl2br(e($text)) !!}</div>
                        @else
                            <p class="placeholder-note mb-0"><i class="bi bi-pencil-square"></i> Belum diisi oleh petugas.</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <aside class="col-lg-5">
                <div class="info-card contact-card">
                    <h3 class="h4 fw-bold mb-3"><i class="bi bi-telephone text-green me-2"></i>Kontak dan Alamat</h3>
                    <ul class="contact-list">
                        <li><span class="ico"><i class="bi bi-geo-alt-fill"></i></span><span>{{ $bpp->address ?: 'Alamat kantor belum diisi' }}</span></li>
                        <li><span class="ico"><i class="bi bi-telephone-fill"></i></span><span>{{ $bpp->phone ?: 'Nomor telepon belum diisi' }}</span></li>
                        <li><span class="ico"><i class="bi bi-envelope-fill"></i></span><span>{{ $bpp->email ?: 'Email belum diisi' }}</span></li>
                        @if (! empty($site['instagram_url']))
                            <li><span class="ico"><i class="bi bi-instagram"></i></span><a href="{{ $site['instagram_url'] }}" target="_blank" rel="noopener">Instagram BPP Routa</a></li>
                        @endif
                    </ul>
                    <div class="contact-map">
                        <iframe src="https://www.google.com/maps?q={{ urlencode($mapsQuery) }}&output=embed&hl=id" title="Lokasi BPP Routa di Google Maps" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                    <a class="btn btn-green w-100" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($mapsQuery) }}">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka di Google Maps
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

@include('partials.struktur')
@endsection
