@extends('admin.layouts.app')

@section('title', $village->exists ? 'Edit Desa' : 'Tambah Desa')

@section('content')
<form method="POST" enctype="multipart/form-data" class="card-box"
      action="{{ $village->exists ? route('admin.villages.update', $village) : route('admin.villages.store') }}">
    @csrf @if ($village->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="name" label="Nama Desa" :value="$village->name" :required="true" />
        <x-field name="description" type="textarea" label="Deskripsi" :value="$village->description" />
        <x-field name="latitude" type="number" step="any" label="Latitude (opsional)" :value="$village->latitude" col="6" help="Isi dari GPS/peta yang benar, atau kosongkan." />
        <x-field name="longitude" type="number" step="any" label="Longitude (opsional)" :value="$village->longitude" col="6" />
        <x-field name="image" type="file" label="Foto Desa" col="6" />
        @if ($village->image)<div class="col-md-6"><img src="{{ $village->image_url }}" class="thumb-lg" alt=""></div>@endif
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.villages.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
