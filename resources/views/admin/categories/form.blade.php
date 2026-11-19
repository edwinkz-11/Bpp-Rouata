@extends('admin.layouts.app')

@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<form method="POST" class="card-box" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf @if ($category->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="name" label="Nama Kategori" :value="$category->name" :required="true" />
        <x-field name="description" type="textarea" rows="3" label="Deskripsi (opsional)" :value="$category->description" />
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.categories.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
