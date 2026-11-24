<article class="card activity-card h-100">
    <a href="{{ route('posts.show', $post->slug) }}" class="card-img-wrap" tabindex="-1" aria-hidden="true">
        <img src="{{ $post->cover_url }}" class="card-img-top" alt="" loading="lazy">
    </a>
    <div class="card-body d-flex flex-column">
        <div class="small text-muted mb-1"><i class="bi bi-calendar3"></i> {{ ($post->published_at ?? $post->created_at)->translatedFormat('d F Y') }}</div>
        <h3 class="h6 fw-bold mb-2"><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
        <p class="small text-muted mb-3">{{ $post->summary }}</p>
        <a href="{{ route('posts.show', $post->slug) }}" class="small fw-semibold mt-auto link-green" aria-label="Baca selengkapnya: {{ $post->title }}">Baca selengkapnya <i class="bi bi-arrow-right"></i></a>
    </div>
</article>
