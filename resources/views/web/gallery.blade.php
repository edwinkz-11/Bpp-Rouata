@extends('layouts.public')

@section('title', 'Galeri Dokumentasi')

@section('content')
@include('partials.page-header', ['title' => 'Galeri Dokumentasi', 'subtitle' => 'Foto-foto kegiatan pertanian di Kecamatan Routa.'])

<section class="section">
    <div class="container">
        @if ($photos->isEmpty())
            <x-empty icon="bi-image" title="Belum ada foto dokumentasi" text="Foto akan tampil di sini setelah diunggah petugas." />
        @else
            <div class="row g-3">
                @foreach ($photos as $photo)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ $photo->url }}" class="gallery-item" data-lightbox="gallery" data-caption="{{ $photo->caption ?: $photo->activity?->title }}">
                            <img src="{{ $photo->url }}" alt="{{ $photo->caption ?: 'Dokumentasi kegiatan' }}" loading="lazy">
                            <span class="zoom"><i class="bi bi-zoom-in"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $photos->links() }}</div>
        @endif
    </div>
</section>
@endsection
