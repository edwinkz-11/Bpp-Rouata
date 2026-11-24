@extends('layouts.public')

@section('title', 'Berita')
@section('description', 'Berita dan kabar terbaru dari BPP Pertanian Routa serta Kementerian Pertanian.')

@section('content')
@include('partials.page-header', ['title' => 'Berita', 'subtitle' => 'Kabar dari BPP Pertanian Routa dan Kementerian Pertanian Republik Indonesia'])

<section class="section">
    <div class="container">
        <div class="section-label"><i class="bi bi-megaphone"></i> Dari BPP Routa</div>
        <h2 class="section-title mb-4">Berita BPP Pertanian Routa</h2>
        @if ($posts->isEmpty())
            <x-empty icon="bi-newspaper" title="Belum ada berita" text="Berita dari BPP akan tampil di sini setelah diterbitkan petugas." />
        @else
            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-lg-4">@include('partials.post-card', ['post' => $post])</div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $posts->links() }}</div>
        @endif
    </div>
</section>

@include('partials.kementan-news', ['news' => $news, 'compact' => false])
<section class="section pt-0">
    <div class="container text-center">
        <a href="https://pertanian.go.id/index.php/news/all/2" target="_blank" rel="noopener" class="btn btn-outline-green">Berita lainnya di pertanian.go.id <i class="bi bi-box-arrow-up-right ms-1"></i></a>
    </div>
</section>
@endsection
