@extends('admin.layouts.app')

@section('title', $activity->exists ? 'Edit Kegiatan' : 'Tambah Kegiatan')

@section('content')
<form method="POST" enctype="multipart/form-data" class="card-box" action="{{ $activity->exists ? route('admin.activities.update', $activity) : route('admin.activities.store') }}">
    @csrf @if ($activity->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="title" label="Judul Kegiatan" :value="$activity->title" :required="true" />
        <x-field name="category_id" type="select" label="Kategori" :options="$categories" :value="$activity->category_id" col="4" />
        <x-field name="activity_date" type="date" label="Tanggal Kegiatan" :value="$activity->activity_date?->format('Y-m-d')" col="4" />
        <x-field name="status" type="select" label="Status" :options="['draft' => 'Draft (tidak tampil di website)', 'published' => 'Terbit (tampil di website)']" :value="$activity->status" :required="true" col="4" />
        <x-field name="location_name" label="Lokasi Kegiatan" :value="$activity->location_name" placeholder="Contoh: nama desa / lokasi" col="8" />
        <x-field name="village_id" type="select" label="Desa (opsional)" :options="$villages" :value="$activity->village_id" col="4" />
        <x-field name="description" type="textarea" rows="7" label="Deskripsi" :value="$activity->description" />

        <x-field name="cover_image" type="file" label="Foto Sampul" col="6" help="Foto utama kegiatan. JPG/PNG/WEBP maks 4 MB." />
        @if ($activity->cover_image)<div class="col-md-6"><div class="small text-muted mb-1">Sampul saat ini</div><img src="{{ $activity->cover_url }}" class="thumb-lg" alt=""></div>@endif

        <div class="col-12">
            <label class="form-label fw-semibold">Tambah Foto Dokumentasi (boleh banyak)</label>
            <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" class="form-control @if ($errors->has('photos') || $errors->has('photos.*')) is-invalid @endif" data-multi-preview="#multi-preview">
            @foreach ($errors->get('photos.*') as $msgs)@foreach ($msgs as $m)<div class="invalid-feedback d-block">{{ $m }}</div>@endforeach @endforeach
            @error('photos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <div class="form-text">Maksimal 20 foto sekaligus, 4 MB per foto. Keterangan tiap foto bisa diubah di menu Galeri Dokumentasi.</div>
            <div id="multi-preview" class="preview-grid"></div>
        </div>

        @if ($activity->exists && $activity->photos->isNotEmpty())
            <div class="col-12">
                <label class="form-label fw-semibold">Foto dokumentasi saat ini <span class="text-muted fw-normal">(centang untuk menghapus)</span></label>
                <div class="preview-grid">
                    @foreach ($activity->photos as $p)
                        <label class="photo-check"><img src="{{ $p->url }}" alt=""><input type="checkbox" name="remove_photos[]" value="{{ $p->id }}"><span><i class="bi bi-trash"></i> Hapus</span></label>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.activities.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
