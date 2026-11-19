@php $compact = $compact ?? false; @endphp
@if (config('kementan.news_enabled'))
<section class="section {{ $compact ? 'bg-green-soft' : '' }}" id="berita">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <div class="section-label"><i class="bi bi-newspaper"></i> Berita Kementan</div>
                <h2 class="section-title mb-0">Kabar Terbaru Kementerian Pertanian</h2>
            </div>
            @if ($compact)
                <a href="{{ route('news') }}" class="btn btn-outline-green">Lihat Semua Berita</a>
            @endif
        </div>

        @if (count($news))
            <div class="row g-4">
                @foreach ($news as $n)
                    <div class="col-md-6 col-lg-4">
                        <article class="card news-card h-100">
                            <a href="{{ $n['url'] }}" target="_blank" rel="noopener nofollow" class="news-thumb">
                                <img src="{{ $n['image'] }}" alt="" loading="lazy" referrerpolicy="no-referrer" onerror="this.parentNode.classList.add('no-img');this.remove()">
                                <i class="bi bi-newspaper news-ph"></i>
                            </a>
                            <div class="card-body d-flex flex-column">
                                @if ($n['date'])<div class="small text-muted mb-1"><i class="bi bi-calendar3"></i> {{ $n['date']->translatedFormat('d F Y') }}</div>@endif
                                <h3 class="h6 fw-bold mb-3"><a href="{{ $n['url'] }}" target="_blank" rel="noopener nofollow">{{ $n['title'] }}</a></h3>
                                <a href="{{ $n['url'] }}" target="_blank" rel="noopener nofollow" class="small fw-semibold mt-auto link-green">Baca di pertanian.go.id <i class="bi bi-box-arrow-up-right"></i></a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <p class="small text-muted mt-3 mb-0"><i class="bi bi-info-circle"></i> Sumber: situs resmi Kementerian Pertanian (pertanian.go.id). Hak cipta berita dan foto milik Kementerian Pertanian.</p>
        @else
            <div class="info-card text-center">
                <p class="mb-3 text-muted">Berita belum dapat dimuat saat ini. Silakan baca langsung di situs resmi Kementerian Pertanian.</p>
                <a href="https://pertanian.go.id/index.php/news/all/2" target="_blank" rel="noopener" class="btn btn-green">Buka Berita Kementan <i class="bi bi-box-arrow-up-right ms-1"></i></a>
            </div>
        @endif
    </div>
</section>
@endif
