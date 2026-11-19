@php
    $dest = urlencode(config('routa.maps_query'));
@endphp
<section class="section pt-0" id="rute">
    <div class="container">
        <h2 class="section-title"><i class="bi bi-signpost-split-fill text-green me-2"></i>Rute dan Estimasi Perjalanan ke Routa</h2>

        <div class="alert alert-warning small d-flex gap-2 align-items-start">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <div>Routa termasuk wilayah yang sulit dijangkau. Data bersumber dari laporan Pengadilan Agama Unaaha (sidang di Routa), BPS Kab. Konawe, dan pemberitaan; sisanya <strong>hitungan perkiraan</strong> (garis lurus × faktor kelokan jalan ÷ kecepatan rata-rata), dengan selisih bisa 20-30%. Kondisi jalan dan cuaca berubah. <strong>Tanyakan kondisi terbaru ke kantor BPP atau kecamatan sebelum berangkat.</strong></div>
        </div>

        <a href="{{ asset('images/rute-akses-routa.svg') }}" data-lightbox="rute" class="d-block mb-2">
            <img src="{{ asset('images/rute-akses-routa.svg') }}" class="img-fluid rounded-3 border w-100" alt="Peta skema rute merah menuju Kecamatan Routa dari Kendari, Unaaha, Kolaka, Konawe Utara, Malili, dan Danau Towuti" loading="lazy">
        </a>
        <p class="small text-muted">Klik gambar untuk memperbesar. Skema tidak berskala.</p>

        <div class="row g-3 mb-4">
            @foreach (config('routa.origins') as $o)
                <div class="col-md-4">
                    <a class="btn btn-outline-green w-100 text-start" target="_blank" rel="noopener"
                       href="https://www.google.com/maps/dir/?api=1&origin={{ urlencode($o['query']) }}&destination={{ $dest }}&travelmode=driving">
                        <i class="bi bi-sign-turn-right-fill me-2"></i><strong>Rute Google Maps dari {{ $o['name'] }}</strong>
                        <span class="d-block small text-muted">{{ $o['note'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>

        <h3 class="h5 fw-bold mb-3">Ringkasan estimasi per rute</h3>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle small route-table">
                <thead class="table-success">
                    <tr><th></th><th>Rute 1: lewat Konawe Utara</th><th>Rute 2: lewat Kolaka, Malili, Danau Towuti</th></tr>
                </thead>
                <tbody>
                    <tr><th>Jalur</th><td>Unaaha → Konawe Utara (Asera) → Wiwirano → jalan tambang/perkebunan → Routa</td><td>Unaaha → Kolaka Timur → Kolaka → Kolaka Utara (Lasusua) → Malili (Luwu Timur) → dermaga Danau Towuti → kapal → Routa</td></tr>
                    <tr><th>Dari Unaaha</th><td>±320 km (BPS)<br>±9 jam, musim kering (laporan)</td><td>±430 km (hitungan)<br>±14 jam jalan + ±3 jam kapal; praktiknya ±2 hari (laporan: 32+ jam)</td></tr>
                    <tr><th>Dari Kendari</th><td>±380 km (+60 km ke Unaaha)<br>±10,5 jam</td><td>±490 km<br>±16 jam jalan + kapal; ±2 hari</td></tr>
                    <tr><th>Dari Kolaka</th><td>±400 km (lewat Unaaha dulu, +77 km)<br>±11 jam</td><td>±350 km (hitungan)<br>±12 jam jalan + ±3 jam kapal; 1-2 hari</td></tr>
                    <tr><th>Tingkat kesulitan</th><td><span class="text-danger fw-bold">4 / 5 (berat)</span><br>●●●●○</td><td><span class="text-danger fw-bold">5 / 5 (sangat berat)</span><br>●●●●●</td></tr>
                    <tr><th>Yang membuatnya sulit</th>
                        <td><ul class="mb-0 ps-3"><li>Beraspal hanya sampai Wiwirano; setelahnya bukit berbatu dan jalan sungai.</li><li>Hanya bisa dilalui musim kering.</li><li>Perlu SUV, double cabin, atau kendaraan off-road.</li></ul></td>
                        <td><ul class="mb-0 ps-3"><li>Tamborasi (Kolaka-Kolaka Utara): sempit, licin, berkelok seperti huruf S, jurang di satu sisi.</li><li>Jalur pesisir Kolut-Malili berbukit, menanjak dan menukik.</li><li>Kapal tradisional Danau Towuti berangkat sekali sehari pukul 11.00 WITA.</li><li>Setelah danau: jalan pengerasan dan jalan desa.</li><li>Dilaporkan ada risiko gangguan keamanan di perbatasan Sultra-Sulsel, terutama kendaraan tunggal malam hari.</li></ul></td></tr>
                </tbody>
            </table>
        </div>

        <h3 class="h5 fw-bold mb-3">Rincian ruas Rute 2 (hitungan perkiraan)</h3>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-striped align-middle small route-table">
                <thead class="table-success"><tr><th>Ruas</th><th class="text-end">Jarak</th><th class="text-end">Waktu</th><th>Kondisi</th></tr></thead>
                <tbody>
                    <tr><td>Unaaha - Kolaka (via Kolaka Timur)</td><td class="text-end">≈ 77 km</td><td class="text-end">≈ 1,7 jam</td><td>Beraspal</td></tr>
                    <tr><td>Kolaka - Lasusua (Kolaka Utara)</td><td class="text-end">≈ 106 km</td><td class="text-end">≈ 2,8 jam</td><td>Beraspal, berkelok, ada ruas sempit dan licin (Tamborasi)</td></tr>
                    <tr><td>Lasusua - Malili (Luwu Timur)</td><td class="text-end">≈ 138 km</td><td class="text-end">≈ 3,6 jam</td><td>Beraspal, perbukitan menanjak dan menukik</td></tr>
                    <tr><td>Malili - dermaga Danau Towuti (Timampu)</td><td class="text-end">≈ 56 km</td><td class="text-end">≈ 1,2 jam</td><td>Beraspal</td></tr>
                    <tr><td>Penyeberangan Danau Towuti</td><td class="text-end">≈ 30 km</td><td class="text-end">≈ 3 jam</td><td>Kapal tradisional, 1x sehari 11.00 WITA</td></tr>
                    <tr><td>Dermaga selatan - Routa</td><td class="text-end">≈ 22 km</td><td class="text-end">≈ 1,4 jam</td><td>Jalan pengerasan / jalan desa</td></tr>
                    <tr class="fw-bold"><td>Total dari Unaaha</td><td class="text-end">≈ 430 km</td><td class="text-end">≈ 14 jam jalan + 3 jam kapal</td><td>Praktiknya ±2 hari karena jadwal kapal dan istirahat</td></tr>
                </tbody>
            </table>
        </div>
        <ul class="small text-muted">
            <li>Asumsi kecepatan rata-rata: aspal berbukit 38-45 km/jam, jalan berat 15 km/jam, kapal 10 km/jam. Faktor kelokan jalan 1,25-1,7 dari jarak garis lurus.</li>
            <li>Rute 1 tidak dirinci per ruas: jarak ±320 km dan waktu ±9 jam mengikuti angka BPS dan laporan Pengadilan Agama Unaaha, sehingga kecepatan rata-ratanya sekitar 36 km/jam. Di musim hujan waktunya bisa jauh lebih lama atau jalan tidak dapat dilalui.</li>
            <li>Garis lurus Unaaha-Routa hanya sekitar 96,2 km (Google Maps), jadi jalan darat jauh lebih panjang karena pegunungan dan hutan lindung di antaranya.</li>
            <li>Rencana jalan poros Latoma-Routa (pemberitaan 2023) belum ada konfirmasi selesai, sehingga tidak dihitung.</li>
        </ul>
    </div>
</section>
