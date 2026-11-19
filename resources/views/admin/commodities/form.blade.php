@extends('admin.layouts.app')

@section('title', $commodity->exists ? 'Edit Komoditas' : 'Tambah Komoditas')

@section('content')
<form method="POST" enctype="multipart/form-data" class="card-box" action="{{ $commodity->exists ? route('admin.commodities.update', $commodity) : route('admin.commodities.store') }}">
    @csrf @if ($commodity->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="name" label="Nama Komoditas" :value="$commodity->name" :required="true" col="6" />
        <x-field name="category" type="select" label="Kategori" :options="\App\Models\Commodity::CATEGORIES" :value="$commodity->category" :required="true" col="6" />
        <x-field name="village_id" type="select" label="Desa pengembangan (opsional)" :options="$villages" :value="$commodity->village_id" col="6" />
        <x-field name="location_note" label="Catatan lokasi pengembangan" :value="$commodity->location_note" col="6" />
        <x-field name="description" type="textarea" label="Deskripsi" :value="$commodity->description" />
        <x-field name="extra_info" type="textarea" label="Informasi tambahan" :value="$commodity->extra_info" help="Contoh: musim tanam, kelompok tani, catatan lain yang sudah pasti benar." />
        <x-field name="status" type="select" label="Status" :options="['active' => 'Aktif (tampil di website)', 'inactive' => 'Nonaktif (disembunyikan)']" :value="$commodity->status" :required="true" col="6" />
        <x-field name="image" type="file" label="Foto" col="6" />
        @if ($commodity->image)<div class="col-md-6"><img src="{{ $commodity->image_url }}" class="thumb-lg" alt=""></div>@endif
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.commodities.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
