@extends('layouts.public')

@section('title', $post->title)
@section('description', $post->summary)
@section('og_type', 'article')
@if ($post->cover_image)
    @section('og_image', $post->cover_url)
@endif

@push('head')
<script type="application/ld+json">{!! json_encode([
    '@@context' => 'https://schema.org',
    '@@type' => 'NewsArticle',
    'headline' => $post->title,
    'datePublished' => ($post->published_at ?? $post->created_at)->toAtomString(),
    'dateModified' => $post->updated_at->toAtomString(),
    'image' => [$post->cover_url],
    'author' => ['@@type' => 'Organization', 'name' => $bpp->name],
    'publisher' => ['@@type' => 'Organization', 'name' => $bpp->name, 'logo' => ['@@type' => 'ImageObject', 'url' => $bpp->logo_url]],
    'mainEntityOfPage' => url()->current(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
@endpush

@section('content')
@include('partials.page-header', ['title' => $post->title, 'parent' => ['Berita', route('news')]])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                @if ($post->cover_image)
                    <img src="{{ $post->cover_url }}" class="img-fluid rounded-4 shadow-soft w-100 cover-img mb-4" alt="Foto sampul: {{ $post->title }}">
                @endif
                <p class="text-muted small"><i class="bi bi-calendar3"></i> {{ ($post->published_at ?? $post->created_at)->translatedFormat('l, d F Y') }}</p>
                <div class="prose">{!! nl2br(e($post->body)) !!}</div>
            </div>
            <aside class="col-lg-4" aria-label="Berita lainnya">
                <div class="info-card">
                    <h2 class="h5 fw-bold mb-3">Berita Lainnya</h2>
                    @forelse ($related as $r)
                        <p class="mb-2"><a href="{{ route('posts.show', $r->slug) }}" class="fw-semibold text-decoration-none">{{ $r->title }}</a><br><small class="text-muted">{{ ($r->published_at ?? $r->created_at)->translatedFormat('d F Y') }}</small></p>
                    @empty
                        <p class="text-muted small mb-0">Belum ada berita lain.</p>
                    @endforelse
                </div>
                <a href="{{ route('news') }}" class="btn btn-outline-green w-100 mt-3"><i class="bi bi-arrow-left me-1"></i> Semua berita</a>
            </aside>
        </div>
    </div>
</section>
@endsection
