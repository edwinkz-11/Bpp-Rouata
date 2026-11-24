@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('content')
@php $v = fn ($k) => $values[$k] ?? null; @endphp
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="card-box mb-3">
        <h2 class="h6 fw-bold mb-3">Tampilan Beranda</h2>
        <div class="row g-3">
            <x-field name="slogan" label="Slogan" :value="$v('slogan')" />
            <x-field name="hero_image" type="file" label="Foto Hero (latar halaman utama)" col="6" help="Disarankan foto lanskap lebar, minimal 1600 px. Kosongkan jika tidak diganti." />
            <x-field name="struktur_image" type="file" label="Gambar Struktur Organisasi" col="6" help="Gambar bagan struktur (JPG/PNG/WebP, maks. 4 MB). Kosongkan jika tidak diganti." />
            <div class="col-md-6"><img src="{{ \App\Services\ImageService::url($v('struktur_image'), 'images/struktur-bpp-routa.jpg') }}" class="thumb-lg" alt=""></div>
            <x-field name="video_tim" type="file" accept="video/mp4,video/webm" label="Video Tim BPP (autoplay saat di-scroll)" col="6" help="MP4 atau WebM, maks. 50 MB. Disarankan ringkas (di bawah 20 MB). Kosongkan jika tidak diganti." />
            <div class="col-md-6"><video src="{{ \App\Models\Setting::videoUrl() }}" class="thumb-lg" controls muted preload="none"></video></div>
            @if ($v('hero_image'))<div class="col-md-6"><img src="{{ \App\Services\ImageService::url($v('hero_image')) }}" class="thumb-lg" alt=""></div>@endif
            <x-field name="footer_text" label="Teks hak cipta (footer)" :value="$v('footer_text')" help="Kosongkan untuk teks otomatis." />
        </div>
    </div>

    <div class="card-box mb-3">
        <h2 class="h6 fw-bold mb-3">Informasi Publik (PPID)</h2>
        <div class="row g-3">
            <x-field name="ppid_intro" type="textarea" rows="6" label="Penjelasan layanan informasi publik" :value="$v('ppid_intro')" help="Tampil di halaman PPID. Isi dengan dasar hukum, prosedur permohonan, dan waktu layanan sesuai ketentuan instansi. Dokumen diunggah lewat menu Dokumen dan PPID." />
        </div>
    </div>

    <div class="card-box mb-3">
        <h2 class="h6 fw-bold mb-3">Media Sosial</h2>
        <div class="row g-3">
            <x-field name="instagram_url" type="url" label="Instagram" :value="$v('instagram_url')" col="6" />
            <x-field name="facebook_url" type="url" label="Facebook" :value="$v('facebook_url')" col="6" />
            <x-field name="youtube_url" type="url" label="YouTube" :value="$v('youtube_url')" col="6" />
            <x-field name="tiktok_url" type="url" label="TikTok" :value="$v('tiktok_url')" col="6" />
        </div>
    </div>

    <div class="card-box mb-3">
        <h2 class="h6 fw-bold mb-3">Peta</h2>
        <div class="row g-3">
            <x-field name="map_center_lat" type="number" step="any" label="Latitude pusat peta" :value="$v('map_center_lat')" col="4" help="Kosongkan untuk pusat bawaan. Jika sudah ada titik lokasi, peta otomatis menyesuaikan." />
            <x-field name="map_center_lng" type="number" step="any" label="Longitude pusat peta" :value="$v('map_center_lng')" col="4" />
            <x-field name="map_zoom" type="number" label="Zoom awal (3–18)" :value="$v('map_zoom')" col="4" />
            <x-field name="boundary_geojson" type="textarea" rows="6" label="Batas wilayah Kecamatan (GeoJSON, opsional)" :value="$v('boundary_geojson')" help="Tempel GeoJSON batas wilayah dari sumber resmi. Jika kosong, batas tidak ditampilkan." />
        </div>
    </div>
    <button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan Pengaturan</button>
</form>
@endsection
