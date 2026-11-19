@extends('layouts.public')

@section('title', 'Berita')

@section('content')
@include('partials.page-header', ['title' => 'Berita', 'subtitle' => 'Kabar terbaru dari Kementerian Pertanian Republik Indonesia'])
@include('partials.kementan-news', ['news' => $news, 'compact' => false])
<section class="section pt-0">
    <div class="container text-center">
        <a href="https://pertanian.go.id/index.php/news/all/2" target="_blank" rel="noopener" class="btn btn-outline-green">Berita lainnya di pertanian.go.id <i class="bi bi-box-arrow-up-right ms-1"></i></a>
    </div>
</section>
@endsection
