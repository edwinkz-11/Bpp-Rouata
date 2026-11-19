<article class="card activity-card h-100">
    <a href="{{ route('activities.show', $activity->slug) }}" class="card-img-wrap">
        <img src="{{ $activity->cover_url }}" class="card-img-top" alt="{{ $activity->title }}" loading="lazy">
        @if ($activity->category)<span class="badge badge-cat">{{ $activity->category->name }}</span>@endif
    </a>
    <div class="card-body d-flex flex-column">
        <h3 class="h6 fw-bold mb-2"><a href="{{ route('activities.show', $activity->slug) }}" class="stretched-link-off">{{ $activity->title }}</a></h3>
        <ul class="list-unstyled small text-muted mb-2 meta-list">
            <li><i class="bi bi-calendar3"></i> {{ $activity->activity_date?->translatedFormat('d F Y') ?? 'Tanggal belum diisi' }}</li>
            <li><i class="bi bi-geo-alt-fill"></i> {{ $activity->location_name ?: 'Lokasi belum diisi' }}</li>
        </ul>
        <p class="small text-muted mb-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) $activity->description), 100) }}</p>
        <a href="{{ route('activities.show', $activity->slug) }}" class="small fw-semibold mt-auto link-green">Baca selengkapnya <i class="bi bi-arrow-right"></i></a>
    </div>
</article>
