/* Editor peta admin: pilih titik lokasi (mode "location") atau gambar jalur (mode "route"). */
(function () {
    const el = document.getElementById('map');
    if (!el || !el.dataset.mode || typeof L === 'undefined') return;

    const mode = el.dataset.mode;
    const cfg = JSON.parse(el.dataset.config || '{}');
    const $ = id => document.getElementById(id);

    const map = L.map(el, { doubleClickZoom: mode !== 'route' }).setView(cfg.center || [-3.6, 122.0], cfg.zoom || 8);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19, attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Lokasi lain yang sudah terdaftar (referensi, abu-abu)
    const ref = [];
    (cfg.others || []).forEach(o => {
        L.circleMarker([o.lat, o.lng], { radius: 6, color: '#475569', weight: 1, fillColor: '#94A3B8', fillOpacity: .7 })
            .bindTooltip(o.name).addTo(map);
        ref.push([o.lat, o.lng]);
    });

    if (cfg.boundary) {
        try { L.geoJSON(cfg.boundary, { style: { color: '#15803D', weight: 2, fill: false, dashArray: '6 4' } }).addTo(map); } catch (e) {}
    }

    function gps(cb) {
        if (!navigator.geolocation) { alert('Perangkat ini tidak mendukung GPS.'); return; }
        navigator.geolocation.getCurrentPosition(
            p => cb(p.coords.latitude, p.coords.longitude, p.coords.accuracy),
            err => alert('Lokasi GPS tidak dapat diambil: ' + err.message + '\nPastikan izin lokasi diaktifkan dan situs dibuka lewat HTTPS.'),
            { enableHighAccuracy: true, timeout: 20000 }
        );
    }

    /* ---------------- MODE LOKASI ---------------- */
    if (mode === 'location') {
        const latIn = $('f-latitude'), lngIn = $('f-longitude');
        let marker = null;

        function place(lat, lng, pan) {
            if (isNaN(lat) || isNaN(lng)) return;
            if (!marker) {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', () => { const p = marker.getLatLng(); fill(p.lat, p.lng); });
            } else {
                marker.setLatLng([lat, lng]);
            }
            if (pan) map.setView([lat, lng], Math.max(map.getZoom(), 15));
        }
        function fill(lat, lng) { latIn.value = lat.toFixed(7); lngIn.value = lng.toFixed(7); }

        map.on('click', e => { fill(e.latlng.lat, e.latlng.lng); place(e.latlng.lat, e.latlng.lng, false); });
        [latIn, lngIn].forEach(i => i.addEventListener('change', () => place(parseFloat(latIn.value), parseFloat(lngIn.value), true)));

        $('btn-gps')?.addEventListener('click', () => gps((lat, lng, acc) => {
            fill(lat, lng); place(lat, lng, true);
            const info = $('gps-info'); if (info) info.textContent = 'Akurasi GPS ± ' + Math.round(acc) + ' m';
        }));

        if (cfg.point) { place(cfg.point[0], cfg.point[1], true); }
        else if (ref.length) { map.fitBounds(L.latLngBounds(ref), { padding: [40, 40], maxZoom: 14 }); }
        return;
    }

    /* ---------------- MODE JALUR ---------------- */
    const input = $('coordinates');
    let pts = [];
    try { pts = JSON.parse(input.value || '[]') || []; } catch (e) { pts = []; }
    pts = pts.filter(p => Array.isArray(p) && p.length >= 2).map(p => [parseFloat(p[0]), parseFloat(p[1])]);

    const line = L.polyline(pts, { color: '#CA8A04', weight: 5, dashArray: cfg.verified ? null : '10 8' }).addTo(map);
    const vertexLayer = L.layerGroup().addTo(map);

    function lengthKm() {
        let m = 0;
        for (let i = 1; i < pts.length; i++) m += map.distance(pts[i - 1], pts[i]);
        return (m / 1000).toFixed(2);
    }

    function write() {
        line.setLatLngs(pts);
        input.value = JSON.stringify(pts.map(p => [+p[0].toFixed(7), +p[1].toFixed(7)]));
        $('pt-count').textContent = pts.length;
        $('pt-length').textContent = lengthKm();
    }

    function vertexIcon(i) {
        const end = i === 0 ? '#15803D' : (i === pts.length - 1 ? '#B91C1C' : '#CA8A04');
        return L.divIcon({
            className: '',
            html: '<div style="width:16px;height:16px;border-radius:50%;background:' + end + ';border:2px solid #fff;box-shadow:0 0 4px rgba(0,0,0,.5)"></div>',
            iconSize: [16, 16], iconAnchor: [8, 8]
        });
    }

    function render() {
        vertexLayer.clearLayers();
        pts.forEach((p, i) => {
            const m = L.marker(p, { draggable: true, icon: vertexIcon(i), title: 'Titik ' + (i + 1) }).addTo(vertexLayer);
            m.on('drag', e => { const ll = e.target.getLatLng(); pts[i] = [ll.lat, ll.lng]; line.setLatLngs(pts); });
            m.on('dragend', () => { write(); render(); });
            m.on('contextmenu', () => { pts.splice(i, 1); write(); render(); });
        });
    }

    function add(lat, lng) { pts.push([lat, lng]); write(); render(); }

    map.on('click', e => add(e.latlng.lat, e.latlng.lng));
    $('btn-undo')?.addEventListener('click', () => { pts.pop(); write(); render(); });
    $('btn-clear')?.addEventListener('click', () => { if (pts.length && confirm('Kosongkan seluruh titik jalur?')) { pts = []; write(); render(); } });
    $('btn-gps')?.addEventListener('click', () => gps((lat, lng) => { add(lat, lng); map.setView([lat, lng], Math.max(map.getZoom(), 16)); }));

    // Impor teks: "lat, lng" per baris, JSON [[lat,lng],...], atau GeoJSON LineString
    function parseText(text) {
        text = text.trim();
        if (!text) return [];
        try {
            let d = JSON.parse(text);
            if (d.type === 'FeatureCollection') d = d.features[0];
            if (d && d.type === 'Feature') d = d.geometry;
            if (d && d.type === 'LineString') return d.coordinates.map(c => [parseFloat(c[1]), parseFloat(c[0])]);
            if (Array.isArray(d)) return d.map(c => [parseFloat(c[0]), parseFloat(c[1])]);
        } catch (e) { /* bukan JSON, coba baris teks */ }
        return text.split(/\r?\n/).map(l => l.split(/[,;\s]+/).filter(Boolean).map(parseFloat)).filter(a => a.length >= 2);
    }

    $('btn-import')?.addEventListener('click', () => {
        const list = parseText($('import-box').value).filter(p => Math.abs(p[0]) <= 90 && Math.abs(p[1]) <= 180 && !isNaN(p[0]) && !isNaN(p[1]));
        if (list.length < 2) { alert('Koordinat tidak terbaca. Minimal 2 titik, format: lat, lng per baris.'); return; }
        if (pts.length && !confirm('Ganti jalur yang sekarang dengan ' + list.length + ' titik dari teks ini?')) return;
        pts = list; write(); render();
        map.fitBounds(line.getBounds(), { padding: [30, 30] });
    });

    write(); render();
    if (pts.length > 1) map.fitBounds(line.getBounds(), { padding: [40, 40] });
    else if (ref.length) map.fitBounds(L.latLngBounds(ref), { padding: [40, 40], maxZoom: 14 });
})();
