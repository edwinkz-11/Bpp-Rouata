@extends('admin.layouts.app')

@section('title', $post->exists ? 'Edit Berita' : 'Tulis Berita')

@section('content')
<form method="POST" enctype="multipart/form-data" class="card-box" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
    @csrf @if ($post->exists) @method('PUT') @endif
    <div class="row g-3">
        <x-field name="title" label="Judul" :value="$post->title" :required="true" />
        <x-field name="excerpt" type="textarea" rows="2" label="Ringkasan (opsional)" :value="$post->excerpt" help="Tampil di kartu berita dan hasil pencarian. Jika kosong, diambil dari awal isi berita." />
        <x-field name="body" type="textarea" rows="14" label="Isi Berita" :value="$post->body" :required="true" help="Tekan Enter dua kali untuk memisahkan paragraf." />
        <x-field name="cover_image" type="file" label="Foto Sampul" col="6" help="JPG, PNG, atau WebP, maks. 4 MB. Foto otomatis diperkecil dan dioptimalkan." />
        @if ($post->cover_image)<div class="col-md-6"><div class="small text-muted mb-1">Sampul saat ini</div><img src="{{ $post->cover_url }}" class="thumb-lg" alt="Sampul saat ini"></div>@endif
        <x-field name="status" type="select" label="Status" :options="['draft' => 'Draft (belum tampil)', 'published' => 'Terbit (tampil di website)']" :value="$post->status" :required="true" col="6" />
        <x-field name="published_at" type="datetime-local" label="Tanggal terbit (opsional)" :value="$post->published_at?->format('Y-m-d\TH:i')" col="6" help="Kosongkan untuk terbit sekarang. Isi tanggal di masa depan untuk menjadwalkan." />
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-green"><i class="bi bi-save me-1"></i> Simpan</button><a href="{{ route('admin.posts.index') }}" class="btn btn-light">Batal</a></div>
</form>
@endsection
