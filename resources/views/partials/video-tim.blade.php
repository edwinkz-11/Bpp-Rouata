{{-- Video otomatis diputar saat masuk layar (lihat public/js/site.js). Dibisukan karena browser hanya mengizinkan autoplay tanpa suara. --}}
<section class="section" id="video-tim">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="section-label"><i class="bi bi-play-circle-fill"></i> Kenali Tim Kami</div>
                <h2 class="section-title">Penyuluh Pertanian {{ $bpp->name }}</h2>
                <p class="text-muted">Tim penyuluh yang mendampingi petani di Kecamatan Routa. Video akan diputar otomatis saat bagian ini terlihat.</p>
                <button type="button" class="btn btn-outline-success btn-sm" data-video-sound="#video-tim-player"><i class="bi bi-volume-mute-fill"></i> <span>Suara mati</span></button>
            </div>
            <div class="col-lg-6">
                <div class="video-frame mx-auto">
                    <video id="video-tim-player" data-autoplay-scroll muted loop playsinline preload="metadata"
                           poster="{{ \App\Models\Setting::videoPosterUrl() }}" controls controlsList="nodownload" width="540" height="675">
                        <source src="{{ \App\Models\Setting::videoUrl() }}" type="video/mp4">
                        Browser Anda tidak mendukung pemutar video.
                    </video>
                </div>
            </div>
        </div>
    </div>
</section>
