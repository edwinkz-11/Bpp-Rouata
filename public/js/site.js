/* Lightbox foto: pakai elemen <a data-lightbox="grup" href="foto-besar" data-caption="..."> */
(function () {
    const modalEl = document.getElementById('lightbox');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    const modal = new bootstrap.Modal(modalEl);
    const img = document.getElementById('lb-img');
    const cap = document.getElementById('lb-caption');
    let items = [];
    let index = 0;

    function show(i) {
        index = (i + items.length) % items.length;
        const el = items[index];
        img.src = el.getAttribute('href');
        img.alt = el.dataset.caption || '';
        cap.textContent = el.dataset.caption || '';
        modalEl.querySelectorAll('.lb-nav').forEach(b => b.classList.toggle('d-none', items.length < 2));
    }

    document.addEventListener('click', function (e) {
        const link = e.target.closest('[data-lightbox]');
        if (!link) return;
        e.preventDefault();
        const group = link.dataset.lightbox;
        items = Array.from(document.querySelectorAll('[data-lightbox="' + group + '"]'));
        show(items.indexOf(link));
        modal.show();
    });

    modalEl.querySelector('.lb-prev').addEventListener('click', () => show(index - 1));
    modalEl.querySelector('.lb-next').addEventListener('click', () => show(index + 1));
    document.addEventListener('keydown', function (e) {
        if (!modalEl.classList.contains('show')) return;
        if (e.key === 'ArrowLeft') show(index - 1);
        if (e.key === 'ArrowRight') show(index + 1);
    });
    modalEl.addEventListener('hidden.bs.modal', () => { img.src = ''; });
})();

/* Video autoplay saat di-scroll: putar ketika >=40% terlihat, jeda ketika keluar layar. */
(function () {
    const videos = document.querySelectorAll('video[data-autoplay-scroll]');
    if (!videos.length) return;
    const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if ('IntersectionObserver' in window && !reduce) {
        const io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                const v = en.target;
                if (en.isIntersecting) {
                    const p = v.play();
                    if (p && p.catch) p.catch(function () { /* diblokir browser: pengguna bisa menekan tombol putar */ });
                } else if (!v.paused) {
                    v.pause();
                }
            });
        }, { threshold: 0.4 });
        videos.forEach(v => io.observe(v));
    }

    document.querySelectorAll('[data-video-sound]').forEach(function (btn) {
        const v = document.querySelector(btn.dataset.videoSound);
        if (!v) return;
        btn.addEventListener('click', function () {
            v.muted = !v.muted;
            btn.querySelector('i').className = v.muted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
            btn.querySelector('span').textContent = v.muted ? 'Suara mati' : 'Suara menyala';
        });
    });
})();
