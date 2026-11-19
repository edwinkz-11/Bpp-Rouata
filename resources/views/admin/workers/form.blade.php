@extends('admin.layouts.app')

@section('title', $worker->exists ? 'Edit Penyuluh' : 'Tambah Penyuluh')

@section('content')
<form method="POST" enctype="multipart/form-data" class="card-box" action="{{ $worker->exists ? route('admin.workers.update', $worker) : route('admin.workers.store') }}">
    @csrf @if ($worker->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="name" label="Nama" :value="$worker->name" :required="true" col="6" />
        <x-field name="position" label="Jabatan" :value="$worker->position" col="6" />
        <x-field name="working_area" label="Wilayah Binaan" :value="$worker->working_area" col="6" />
        <x-field name="sort_order" type="number" label="Urutan tampil" :value="$worker->sort_order ?? 0" col="6" help="Angka kecil tampil lebih dulu." />
        <x-field name="bio" type="textarea" rows="3" label="Keterangan singkat" :value="$worker->bio" />
        <x-field name="photo" type="file" label="Foto" col="6" />
        @if ($worker->photo)<div class="col-md-6"><img src="{{ $worker->photo_url }}" class="thumb-lg" alt=""></div>@endif
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.workers.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
