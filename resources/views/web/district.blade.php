@extends('layouts.public')

@section('title', 'Profil Kecamatan')

@section('content')
@include('partials.page-header', ['title' => 'Profil Kecamatan ' . str_replace('Kecamatan ', '', $district->name), 'subtitle' => 'Mengenal wilayah, masyarakat, dan potensi pertanian Routa.'])

@php
    $blocks = [
        ['Gambaran Umum', 'bi-card-text', $district->description],
        ['Sejarah Singkat', 'bi-hourglass-split', $district->history],
        ['Letak Geografis', 'bi-compass', $district->geography],
        ['Kondisi Lingkungan dan Wilayah', 'bi-tree', $district->environment],
        ['Kondisi Masyarakat', 'bi-people', $district->society],
        ['Potensi Sektor Pertanian', 'bi-flower1', $district->agriculture_potential],
    ];
@endphp

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <img src="{{ $district->cover_url }}" class="img-fluid rounded-4 shadow-soft w-100 cover-img mb-4" alt="Wilayah {{ $district->name }}">
                <div class="row g-3">
                    @foreach ($blocks as [$title, $icon, $text])
                        <div class="col-12">
                            <div class="info-card">
                                <h2 class="h5 fw-bold"><i class="bi {{ $icon }} text-green me-2"></i>{{ $title }}</h2>
                                @if ($text)
                                    <div class="text-muted prose">{!! nl2br(e($text)) !!}</div>
                                @else
                                    <p class="placeholder-note mb-0"><i class="bi bi-pencil-square"></i> Informasi ini belum diisi oleh petugas.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="info-card sticky-lg-top" style="top: 100px">
                    <h2 class="h5 fw-bold"><i class="bi bi-houses text-green me-2"></i>Daftar Desa</h2>
                    @if ($villages->isEmpty())
                        <p class="placeholder-note mb-0"><i class="bi bi-pencil-square"></i> Daftar desa belum diisi.</p>
                    @else
                        <p class="small text-muted">{{ $villages->count() }} desa/kelurahan tercatat.</p>
                        <ul class="list-group list-group-flush">
                            @foreach ($villages as $v)
                                <li class="list-group-item px-0">
                                    <strong>{{ $v->name }}</strong>
                                    @if ($v->description)<div class="small text-muted">{{ \Illuminate\Support\Str::limit($v->description, 90) }}</div>@endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ route('map') }}" class="btn btn-green w-100 mt-3"><i class="bi bi-geo-alt-fill me-1"></i> Lihat di Peta</a>
                </div>
            </aside>
        </div>
    </div>
</section>

@if ($commodities->isNotEmpty())
<section class="section bg-green-soft">
    <div class="container">
        <h2 class="section-title">Komoditas di Routa</h2>
        <div class="row g-3">
            @foreach ($commodities as $c)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="commodity-mini"><img src="{{ $c->image_url }}" alt="{{ $c->name }}" loading="lazy"><strong>{{ $c->name }}</strong><small>{{ $c->category }}</small></div>
                </div>
            @endforeach
        </div>
        <a href="{{ route('commodities.index') }}" class="btn btn-outline-green mt-4">Lihat Semua Potensi</a>
    </div>
</section>
@endif
@include('partials.pemandangan')
@endsection
