{{-- Struktur organisasi BPP. Klik gambar untuk memperbesar. --}}
<section class="section bg-green-soft" id="struktur">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-label justify-content-center"><i class="bi bi-diagram-3-fill"></i> Struktur Organisasi</div>
            <h2 class="section-title">Struktur {{ $bpp->name }}</h2>
        </div>
        <a href="{{ \App\Models\Setting::strukturUrl() }}" data-lightbox="struktur" data-caption="Struktur organisasi {{ $bpp->name }}" class="struktur-frame d-block mx-auto" title="Klik untuk memperbesar">
            <img src="{{ \App\Models\Setting::strukturUrl() }}" alt="Bagan struktur organisasi {{ $bpp->name }}" class="img-fluid" loading="lazy" width="1600" height="1200">
            <span class="struktur-zoom"><i class="bi bi-arrows-fullscreen"></i> Perbesar</span>
        </a>
    </div>
</section>
