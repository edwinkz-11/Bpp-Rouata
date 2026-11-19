/* Peta publik BPP Routa — Leaflet + OpenStreetMap */
(function () {
    const el = document.getElementById('map');
    if (!el || typeof L === 'undefined' || el.dataset.mode) return; // data-mode = peta admin

    const cfg = JSON.parse(el.dataset.config || '{}');
    const cats = cfg.categories || {};
    const map = L.map(el, { scrollWheelZoom: !cfg.mini }).setView(cfg.center || [-3.6, 122.0], cfg.zoom || 8);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const badge = v => v ? '<span class="badge text-bg-success">Terverifikasi</span>'
                         : '<span class="badge text-bg-warning">Belum Terverifikasi</span>';

    const panel = document.getElementById('info-panel');
    const filtersEl = document.getElementById('map-filters');
    const legendEl = document.getElementById('map-legend');
    const emptyEl = document.getElementById('map-empty');

    const groups = {};      // kategori -> layerGroup
    let routeGroup = L.layerGroup().addTo(map);
    const active = new Set();

    function pinIcon(cat, verified) {
        const c = cats[cat] || cats.lainnya || { color: '#64748B', icon: 'bi-geo-alt-fill' };
        return L.divIcon({
            className: '',
            html: '<div class="map-pin ' + (verified ? '' : 'unverified') + '" style="background:' + c.color + '"><i class="bi ' + c.icon + '"></i></div>',
            iconSize: [34, 34], iconAnchor: [17, 34], popupAnchor: [0, -32]
        });
    }

    function detailHtml(d, withHeading) {
        return (d.image ? '<img class="popup-img" src="' + esc(d.image) + '" alt="' + esc(d.name) + '">' : '') +
            (withHeading ? '<h2 class="h5 fw-bold">' + esc(d.name) + '</h2>' : '<strong>' + esc(d.name) + '</strong><br>') +
            '<div class="mb-2"><span class="badge badge-soft">' + esc(d.category_label || 'Jalur') + '</span> ' + badge(d.verified) + '</div>' +
            (d.village ? '<div class="small text-muted mb-1"><i class="bi bi-houses"></i> ' + esc(d.village) + '</div>' : '') +
            (d.description ? '<p class="small mb-1">' + esc(d.description).replace(/\n/g, '<br>') + '</p>' : '') +
            (d.extra_info ? '<p class="small text-muted mb-1">' + esc(d.extra_info).replace(/\n/g, '<br>') + '</p>' : '') +
            '<div class="small text-muted">Koordinat: ' + (d.lat !== undefined ? d.lat.toFixed(6) + ', ' + d.lng.toFixed(6) : '-') + '</div>';
    }

    function showPanel(d) {
        if (!panel) return;
        panel.innerHTML = '<h2 class="h6 text-green fw-bold mb-3"><i class="bi bi-geo-alt-fill me-1"></i>Detail Lokasi</h2>' + detailHtml(d, true);
    }

    function applyFilters() {
        Object.entries(groups).forEach(([cat, g]) => {
            if (active.has(cat)) { if (!map.hasLayer(g)) g.addTo(map); } else { map.removeLayer(g); }
        });
        if (active.has('__routes')) { if (!map.hasLayer(routeGroup)) routeGroup.addTo(map); } else { map.removeLayer(routeGroup); }
        filtersEl && filtersEl.querySelectorAll('.chip').forEach(ch => ch.classList.toggle('active', active.has(ch.dataset.cat)));
    }

    function buildFilters(presentCats, hasRoutes) {
        if (legendEl) {
            legendEl.innerHTML = Object.entries(cats).map(([k, c]) =>
                '<li><span class="dot" style="background:' + c.color + '"></span>' + esc(c.label) + '</li>').join('') +
                '<li><span class="line"></span>Jalur terverifikasi</li><li><span class="line dash"></span>Jalur belum terverifikasi</li>';
        }
        if (!filtersEl) return;
        const chips = presentCats.map(k => '<button type="button" class="chip active" data-cat="' + k + '">' + esc(cats[k]?.label || k) + '</button>');
        if (hasRoutes) chips.push('<button type="button" class="chip active" data-cat="__routes"><i class="bi bi-signpost-split"></i> Jalur</button>');
        filtersEl.innerHTML = chips.join('');
        filtersEl.addEventListener('click', e => {
            const b = e.target.closest('.chip'); if (!b) return;
            const k = b.dataset.cat;
            active.has(k) ? active.delete(k) : active.add(k);
            applyFilters();
        });
    }

    fetch(cfg.dataUrl, { headers: { Accept: 'application/json' } })
        .then(r => r.json())
        .then(data => {
            const bounds = [];

            if (data.boundary) {
                try {
                    const b = L.geoJSON(data.boundary, { style: { color: '#14532D', weight: 2.5, fillColor: '#15803D', fillOpacity: .06, dashArray: '6 4' } }).addTo(map);
                    if (b.getBounds().isValid()) bounds.push(b.getBounds());
                } catch (e) { /* GeoJSON tidak valid: abaikan */ }
            }

            (data.locations || []).forEach(d => {
                const g = groups[d.category] || (groups[d.category] = L.layerGroup().addTo(map));
                const m = L.marker([d.lat, d.lng], { icon: pinIcon(d.category, d.verified), title: d.name }).addTo(g);
                m.bindPopup(detailHtml(d, false), { maxWidth: 240 });
                m.on('click', () => showPanel(d));
                bounds.push(L.latLng(d.lat, d.lng));
                active.add(d.category);
            });

            (data.routes || []).forEach(r => {
                if (!r.points || r.points.length < 2) return;
                const line = L.polyline(r.points, {
                    color: '#CA8A04', weight: 5, opacity: .9, dashArray: r.verified ? null : '10 8'
                }).addTo(routeGroup);
                line.bindPopup('<strong>' + esc(r.name) + '</strong><br>' + badge(r.verified) +
                    (r.description ? '<p class="small mt-1 mb-0">' + esc(r.description) + '</p>' : ''));
                line.on('click', () => showPanel({ name: r.name, category_label: 'Jalur', description: r.description, verified: r.verified }));
                bounds.push(line.getBounds());
            });
            if ((data.routes || []).length) active.add('__routes');

            buildFilters(Object.keys(groups), (data.routes || []).length > 0);

            if (bounds.length) {
                const fb = L.latLngBounds([]);
                bounds.forEach(b => fb.extend(b));
                if (fb.isValid()) map.fitBounds(fb, { padding: [40, 40], maxZoom: 15 });
            } else if (emptyEl) {
                emptyEl.classList.remove('d-none');
            }
        })
        .catch(() => { emptyEl && (emptyEl.textContent = 'Data peta gagal dimuat. Muat ulang halaman.', emptyEl.classList.remove('d-none')); });
})();
