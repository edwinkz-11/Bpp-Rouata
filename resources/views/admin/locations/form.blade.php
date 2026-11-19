@extends('admin.layouts.app')

@section('title', $location->exists ? 'Edit Titik Lokasi' : 'Tambah Titik Lokasi')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}">
    @csrf @if ($location->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card-box h-100">
                <h2 class="h6 fw-bold mb-1"><i class="bi bi-cursor text-green"></i> Tentukan titik di peta</h2>
                <p class="small text-muted">Klik peta untuk menaruh penanda, geser penanda untuk menyesuaikan, atau isi latitude/longitude secara manual. Di lapangan, tombol GPS memakai posisi perangkat Anda.</p>
                <div id="map" class="map-box" style="height: 440px" data-mode="location" data-config='@json($mapConfig)'></div>
                <div class="d-flex gap-2 mt-2 flex-wrap">
                    <button type="button" class="btn btn-outline-green btn-sm" id="btn-gps"><i class="bi bi-crosshair"></i> Gunakan lokasi GPS saya</button>
                    <span class="small text-muted align-self-center" id="gps-info"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card-box">
                <div class="row g-3">
                    <x-field name="name" label="Nama Lokasi" :value="$location->name" :required="true" />
                    <x-field name="category" type="select" label="Kategori" :options="collect(\App\Models\Location::CATEGORIES)->map(fn ($c) => $c['label'])" :value="$location->category" :required="true" col="6" />
                    <x-field name="village_id" type="select" label="Desa" :options="$villages" :value="$location->village_id" col="6" />
                    <x-field name="latitude" type="number" step="any" label="Latitude" :value="$location->latitude" :required="true" col="6" />
                    <x-field name="longitude" type="number" step="any" label="Longitude" :value="$location->longitude" :required="true" col="6" />
                    <x-field name="description" type="textarea" rows="3" label="Deskripsi" :value="$location->description" />
                    <x-field name="extra_info" type="textarea" rows="2" label="Informasi tambahan" :value="$location->extra_info" />
                    <x-field name="verification_status" type="select" label="Status verifikasi" :options="\App\Models\Location::STATUSES" :value="$location->verification_status" :required="true" help="“Draft” tidak pernah tampil ke pengunjung. Pilih “Terverifikasi” hanya jika sudah dicek di lapangan." />
                    <x-field name="is_published" type="checkbox" label="Tampilkan di peta publik" :value="$location->is_published" />
                    <x-field name="image" type="file" label="Foto lokasi" />
                    @if ($location->image)<div class="col-12"><img src="{{ $location->image_url }}" class="thumb-lg" alt=""></div>@endif
                </div>
                <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.locations.index') }}" class="btn btn-light">Batal</a></div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/admin-map.js') }}"></script>
@endpush
