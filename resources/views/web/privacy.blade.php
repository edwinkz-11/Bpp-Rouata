@extends('layouts.public')

@section('title', 'Kebijakan Privasi')
@section('description', 'Cara BPP Pertanian Routa mengumpulkan, menggunakan, dan melindungi data pengunjung website.')

@section('content')
@include('partials.page-header', ['title' => 'Kebijakan Privasi', 'subtitle' => 'Data apa yang kami kumpulkan dan bagaimana kami menjaganya.'])

{{-- Naskah ini adalah draf awal. Instansi perlu meninjau dan menyesuaikannya sebelum situs dipakai resmi. --}}
<section class="section">
    <div class="container" style="max-width: 860px">
        <div class="info-card prose">
            <h2 class="h5 fw-bold">1. Data yang kami kumpulkan</h2>
            <p><strong>Formulir kontak dan pengaduan.</strong> Nama, email atau nomor telepon, jenis pesan, subjek, dan isi pesan yang Anda tulis sendiri. Kami tidak meminta data lain.</p>
            <p><strong>Statistik kunjungan.</strong> Kami menghitung jumlah kunjungan per halaman per hari. Hitungan ini tidak menyimpan alamat IP, tidak memakai cookie pelacak, dan tidak dapat dikaitkan dengan individu.</p>
            <p><strong>Akun petugas.</strong> Nama, email, dan peran petugas yang mengelola website, beserta catatan aktivitas pengelolaan (siapa mengubah data apa dan kapan) untuk keperluan keamanan dan akuntabilitas.</p>

            <h2 class="h5 fw-bold mt-4">2. Cookie</h2>
            <p>Website hanya memakai cookie fungsional, yaitu sesi dan token keamanan formulir. Kami tidak memakai cookie iklan atau pelacakan.</p>

            <h2 class="h5 fw-bold mt-4">3. Tujuan penggunaan</h2>
            <p>Data pesan dipakai untuk menindaklanjuti pertanyaan, saran, pengaduan, atau permohonan informasi Anda. Data tidak dijual, tidak dibagikan untuk keperluan komersial, dan tidak dipakai untuk tujuan lain tanpa persetujuan Anda, kecuali diwajibkan oleh peraturan perundang-undangan.</p>

            <h2 class="h5 fw-bold mt-4">4. Layanan pihak ketiga</h2>
            <p>Beberapa halaman menampilkan peta dari Google Maps dan OpenStreetMap, serta daftar berita dari situs Kementerian Pertanian. Saat Anda membuka bagian tersebut, peramban Anda terhubung ke layanan itu dan tunduk pada kebijakan privasi masing-masing. Huruf, ikon, dan skrip tampilan website ini dihosting sendiri, bukan dari CDN pihak ketiga.</p>

            <h2 class="h5 fw-bold mt-4">5. Penyimpanan dan keamanan</h2>
            <p>Pesan disimpan di server instansi dan hanya dapat dibuka oleh petugas yang berwenang. Pesan yang sudah selesai ditindaklanjuti dapat dihapus petugas atas permintaan Anda.</p>

            <h2 class="h5 fw-bold mt-4">6. Hak Anda</h2>
            <p>Sesuai Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi, Anda berhak meminta akses, perbaikan, atau penghapusan data pribadi yang Anda kirim. Hubungi kami lewat <a href="{{ route('contact') }}">formulir kontak</a>@if ($bpp->email) atau email {{ $bpp->email }}@endif.</p>

            <h2 class="h5 fw-bold mt-4">7. Perubahan kebijakan</h2>
            <p class="mb-0">Kebijakan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu ditampilkan di halaman ini.</p>
        </div>
    </div>
</section>
@endsection
