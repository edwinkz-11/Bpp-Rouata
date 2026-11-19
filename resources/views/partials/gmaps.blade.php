@php
    $dest = config('routa.maps_query');
    $tabs = [['Lokasi Routa', 'https://www.google.com/maps?q=' . urlencode($dest) . '&output=embed&hl=id']];
    foreach (config('routa.origins') as $o) {
        $tabs[] = ['Rute dari ' . $o['name'], 'https://maps.google.com/maps?saddr=' . urlencode($o['query']) . '&daddr=' . urlencode($dest) . '&output=embed&hl=id'];
    }
    $open = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($dest);
@endphp
<section class="section">
    <div class="container">
        <h2 class="section-title"><i class="bi bi-google text-green me-2"></i>Google Maps</h2>
        <div class="chip-row mb-3" id="gmap-tabs">
            @foreach ($tabs as $i => [$label, $src])
                <button type="button" class="btn btn-sm {{ $i === 0 ? 'btn-green' : 'btn-outline-green' }}" data-src="{{ $src }}">{{ $label }}</button>
            @endforeach
        </div>
        <div class="gmap-frame">
            <iframe id="gmap-frame" src="{{ $tabs[0][1] }}" title="Google Maps Kecamatan Routa" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-3 align-items-center">
            <a class="btn btn-green" href="{{ $open }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right me-1"></i>Buka di Google Maps</a>
            <span class="small text-muted">Rute di Google Maps dihitung otomatis dan bisa berbeda dari skema di bawah; kondisi jalan Routa sering tidak terekam.</span>
        </div>
    </div>
</section>
<script>
document.querySelectorAll('#gmap-tabs button').forEach(function (b) {
    b.addEventListener('click', function () {
        document.getElementById('gmap-frame').src = b.dataset.src;
        document.querySelectorAll('#gmap-tabs button').forEach(function (x) { x.className = 'btn btn-sm btn-outline-green'; });
        b.className = 'btn btn-sm btn-green';
    });
});
</script>
