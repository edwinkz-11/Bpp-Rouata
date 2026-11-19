<section class="section">
    <div class="container">
        <div class="section-label"><i class="bi bi-camera-fill"></i> Pemandangan Routa</div>
        <h2 class="section-title">Wilayah Kecamatan Routa</h2>
        <div class="row g-3">
            @foreach ([['routa-danau.jpg', 'Danau berair biru jernih dikelilingi hutan'], ['routa-padang.jpg', 'Padang rumput, danau, dan perahu dengan latar perbukitan'], ['routa-udara-2.jpg', 'Foto udara permukiman dan lembah Routa'], ['routa-udara-1.jpg', 'Foto udara permukiman dan perkebunan Routa'], ['routa-satelit.jpg', 'Citra satelit wilayah Kecamatan Routa (tangkapan Google Maps)']] as [$f, $cap])
                <div class="col-md-4">
                    <a href="{{ asset('images/'.$f) }}" data-lightbox="routa" data-caption="{{ $cap }}" class="gallery-item d-block">
                        <img src="{{ asset('images/'.$f) }}" alt="{{ $cap }}" class="img-fluid rounded-3 w-100" style="aspect-ratio:4/3;object-fit:cover" loading="lazy">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
