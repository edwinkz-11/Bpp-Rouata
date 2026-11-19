(function () {
    // Konfirmasi hapus: <form data-confirm="Pesan">
    document.addEventListener('submit', function (e) {
        const msg = e.target.dataset && e.target.dataset.confirm;
        if (msg && !window.confirm(msg)) e.preventDefault();
    });

    // Sidebar mobile
    const side = document.getElementById('sidebar');
    const backdrop = document.getElementById('sideBackdrop');
    const toggle = document.getElementById('sideToggle');
    const close = () => { side.classList.remove('open'); backdrop.classList.remove('show'); };
    toggle && toggle.addEventListener('click', () => { side.classList.toggle('open'); backdrop.classList.toggle('show'); });
    backdrop && backdrop.addEventListener('click', close);

    // Pratinjau satu foto
    document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            const img = document.querySelector(input.dataset.preview);
            if (!img) return;
            if (input.files[0]) { img.src = URL.createObjectURL(input.files[0]); img.classList.remove('d-none'); }
            else img.classList.add('d-none');
        });
    });

    // Pratinjau banyak foto
    document.querySelectorAll('input[type=file][data-multi-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            const box = document.querySelector(input.dataset.multiPreview);
            if (!box) return;
            box.innerHTML = '';
            Array.from(input.files).slice(0, 30).forEach(function (f) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(f);
                img.alt = f.name;
                box.appendChild(img);
            });
        });
    });
})();
