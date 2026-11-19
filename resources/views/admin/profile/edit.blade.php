@extends('admin.layouts.app')

@php $isBpp = $type === 'bpp'; @endphp
@section('title', $isBpp ? 'Profil BPP' : 'Profil Kecamatan')

@section('content')
<form method="POST" action="{{ route('admin.profile.update', $type) }}" enctype="multipart/form-data" class="card-box">
    @csrf @method('PUT')
    <div class="row g-3">
        <x-field name="name" label="{{ $isBpp ? 'Nama BPP' : 'Nama Kecamatan' }}" :value="$profile->name" :required="true" col="6" />
        @if ($isBpp)
            <x-field name="institution_name" label="Nama Instansi Lengkap" :value="$profile->institution_name" col="6" />
        @endif
        <x-field name="description" type="textarea" rows="5" label="{{ $isBpp ? 'Profil BPP' : 'Gambaran Umum Kecamatan' }}" :value="$profile->description" />
        <x-field name="history" type="textarea" rows="5" label="Sejarah Singkat" :value="$profile->history" help="Isi hanya dengan informasi yang sudah pasti benar." />

        @if ($isBpp)
            <x-field name="vision" type="textarea" label="Visi" :value="$profile->vision" col="6" />
            <x-field name="mission" type="textarea" label="Misi (satu poin per baris)" :value="$profile->mission" col="6" />
            <x-field name="tasks_functions" type="textarea" rows="5" label="Tugas dan Fungsi" :value="$profile->tasks_functions" />
            <x-field name="address" type="textarea" rows="2" label="Alamat Kantor" :value="$profile->address" />
            <x-field name="phone" label="Nomor Telepon" :value="$profile->phone" col="6" />
            <x-field name="email" type="email" label="Email" :value="$profile->email" col="6" />
            <x-field name="logo" type="file" label="Logo BPP" col="6" help="JPG/PNG/WEBP, maks 4 MB. Kosongkan jika tidak diganti." />
            @if ($profile->logo)<div class="col-md-6"><div class="small text-muted mb-1">Logo saat ini</div><img src="{{ $profile->logo_url }}" class="thumb-lg" alt="Logo"></div>@endif
        @else
            <x-field name="geography" type="textarea" rows="4" label="Letak Geografis" :value="$profile->geography" />
            <x-field name="environment" type="textarea" rows="4" label="Kondisi Lingkungan dan Wilayah" :value="$profile->environment" />
            <x-field name="society" type="textarea" rows="4" label="Kondisi Masyarakat" :value="$profile->society" />
            <x-field name="agriculture_potential" type="textarea" rows="4" label="Potensi Sektor Pertanian" :value="$profile->agriculture_potential" />
        @endif

        <x-field name="cover_image" type="file" :label="$isBpp ? 'Foto Utama BPP' : 'Foto Utama Kecamatan'" col="6" help="Tampil di halaman publik. Kosongkan jika tidak diganti." />
        @if ($profile->cover_image)<div class="col-md-6"><div class="small text-muted mb-1">Foto saat ini</div><img src="{{ $profile->cover_url }}" class="thumb-lg" alt="Foto utama"></div>@endif
    </div>
    <div class="mt-4"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button></div>
</form>
@endsection
