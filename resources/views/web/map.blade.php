@extends('layouts.public')

@section('title', 'Peta Wilayah')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
@include('partials.page-header', ['title' => 'Peta Wilayah', 'subtitle' => 'Titik lokasi dan jalur pertanian Kecamatan Routa. Klik penanda untuk melihat informasinya.'])

<section class="section">
    <div class="container">
        <div class="alert alert-light border small d-flex gap-2 align-items-start">
            <i class="bi bi-info-circle-fill text-green mt-1"></i>
            <div>Pemetaan dilakukan bertahap oleh petugas. Titik atau jalur berlabel <span class="badge text-bg-warning">Belum Terverifikasi</span> masih dalam pengecekan lapangan. Jika belum ada titik, peta hanya menampilkan peta dasar OpenStreetMap.</div>
        </div>

        <div class="chip-row mb-3" id="map-filters" aria-label="Filter kategori lokasi"></div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div id="map" class="map-box map-lg" data-config='@json($mapConfig)'></div>
                <div id="map-empty" class="placeholder-note mt-2 d-none"><i class="bi bi-geo"></i> Belum ada titik lokasi atau jalur yang diterbitkan.</div>
            </div>
            <div class="col-lg-4">
                <div class="info-card" id="info-panel">
                    <h2 class="h5 fw-bold"><i class="bi bi-cursor-fill text-green me-2"></i>Detail Lokasi</h2>
                    <p class="text-muted small mb-0">Klik salah satu penanda atau jalur pada peta untuk melihat nama, kategori, foto, dan keterangannya di sini.</p>
                </div>
                <div class="info-card mt-3">
                    <h2 class="h6 fw-bold">Legenda</h2>
                    <ul class="list-unstyled small mb-0 legend" id="map-legend"></ul>
                </div>
            </div>
        </div>
    </div>
</section>
@include('partials.gmaps')
@include('partials.rute-akses')
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/public-map.js') }}"></script>
@endpush
