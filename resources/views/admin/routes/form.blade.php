@extends('admin.layouts.app')

@section('title', $route->exists ? 'Edit Jalur' : 'Tambah Jalur')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
<form method="POST" action="{{ $route->exists ? route('admin.routes.update', $route) : route('admin.routes.store') }}">
    @csrf @if ($route->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card-box">
                <h2 class="h6 fw-bold mb-1"><i class="bi bi-pencil-square text-green"></i> Gambar jalur</h2>
                <p class="small text-muted">Klik peta untuk menambah titik berurutan dari awal sampai akhir jalur. Geser titik untuk memperbaiki, klik kanan (tekan lama di HP) pada titik untuk menghapusnya. Titik abu-abu adalah lokasi yang sudah terdaftar.</p>
                <div id="map" class="map-box" style="height: 520px" data-mode="route" data-config='@json($mapConfig)'></div>
                <div class="d-flex gap-2 mt-2 flex-wrap align-items-center">
                    <button type="button" class="btn btn-outline-green btn-sm" id="btn-gps"><i class="bi bi-crosshair"></i> Tambah titik dari GPS saya</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-undo"><i class="bi bi-arrow-counterclockwise"></i> Hapus titik terakhir</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btn-clear"><i class="bi bi-eraser"></i> Kosongkan</button>
                    <span class="small text-muted ms-auto">Jumlah titik: <strong id="pt-count">0</strong> · Panjang ± <strong id="pt-length">0</strong> km</span>
                </div>
                @php
                    // Setelah gagal validasi, old('coordinates') bisa berupa array (sudah dinormalisasi request).
                    $coordValue = old('coordinates', $route->coordinates ?? []);
                    $coordValue = is_array($coordValue) ? json_encode($coordValue) : $coordValue;
                @endphp
                <input type="hidden" name="coordinates" id="coordinates" value="{{ $coordValue }}">
                @error('coordinates')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                @foreach ($errors->get('coordinates.*') as $msgs)@foreach ($msgs as $m)<div class="text-danger small">{{ $m }}</div>@endforeach @endforeach

                <details class="mt-3">
                    <summary class="small fw-semibold">Tempel koordinat dari GPS / file GeoJSON</summary>
                    <textarea id="import-box" rows="4" class="form-control mt-2" placeholder='Satu titik per baris: -3.123456, 121.123456&#10;atau tempel GeoJSON LineString'></textarea>
                    <button type="button" class="btn btn-outline-green btn-sm mt-2" id="btn-import">Gunakan koordinat ini</button>
                </details>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-box">
                <div class="row g-3">
                    <x-field name="name" label="Nama Jalur" :value="$route->name" :required="true" />
                    <x-field name="start_location_id" type="select" label="Lokasi awal (opsional)" :options="$locationOptions" :value="$route->start_location_id" />
                    <x-field name="end_location_id" type="select" label="Lokasi akhir (opsional)" :options="$locationOptions" :value="$route->end_location_id" />
                    <x-field name="description" type="textarea" rows="3" label="Deskripsi / kondisi jalan" :value="$route->description" />
                    <x-field name="verification_status" type="select" label="Status verifikasi" :options="\App\Models\Location::STATUSES" :value="$route->verification_status" :required="true" help="Jalur “Belum Terverifikasi” tampil dengan garis putus-putus dan label peringatan." />
                    <x-field name="is_published" type="checkbox" label="Tampilkan di peta publik" :value="$route->is_published" />
                </div>
                <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.routes.index') }}" class="btn btn-light">Batal</a></div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/admin-map.js') }}"></script>
@endpush
