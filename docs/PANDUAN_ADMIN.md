# Panduan Admin dan Pengelola

Panduan singkat untuk petugas BPP yang mengelola website lewat panel `/admin`.

## Masuk dan keamanan akun
- Buka `/admin`, masuk dengan email dan kata sandi dari admin.
- Setelah login pertama, ganti kata sandi di menu **Akun Saya**. Gunakan kata sandi unik, minimal 8 karakter, jangan dibagi ke orang lain.
- Login dikunci sementara setelah 5 kali salah. Tunggu beberapa saat lalu coba lagi.
- Peran **Admin** bisa mengelola pengguna dan melihat Log Aktivitas. Peran **Editor** mengelola konten, dan hanya boleh menghapus data buatannya sendiri.

## Menulis berita BPP
1. Menu **Berita BPP**, klik **Tulis Berita**.
2. Isi judul, ringkasan (opsional), isi berita, dan foto sampul.
3. Status **Draft** tidak tampil di website. Pilih **Terbit** untuk menayangkan.
4. Isi **Tanggal terbit** di masa depan untuk menjadwalkan. Kosongkan untuk terbit sekarang.
5. Alamat (slug) berita tidak berubah setelah terbit, jadi tautan yang sudah dibagikan tidak mati.

Foto yang diunggah otomatis diperkecil (maks. 1600 px) dan diubah ke WebP. Data lokasi GPS di foto ikut terhapus.

## Mengunggah dokumen dan mengelola PPID
1. Menu **Dokumen dan PPID**, klik **Tambah Dokumen**.
2. Pilih kategori. Tiga kategori **Informasi Berkala**, **Informasi Setiap Saat**, dan **Informasi Serta Merta** otomatis tampil di halaman PPID. Kategori lain hanya tampil di halaman Dokumen.
3. Unggah file (PDF, Word, Excel, PowerPoint, JPG, PNG, maks. 10 MB). Sistem memeriksa isi file, bukan hanya namanya.
4. Hanya unggah dokumen yang sudah resmi dan boleh dipublikasikan. Hilangkan centang **Tampilkan di website** untuk menyembunyikan.
5. Teks penjelasan di halaman PPID (dasar hukum, prosedur, waktu layanan) diisi di **Pengaturan Website**, bagian Informasi Publik.

## Pesan masuk dan pengaduan
- Pesan dari formulir `/kontak` muncul di menu **Pesan Masuk**. Angka kuning di menu adalah jumlah pesan yang belum dibaca.
- Membuka pesan otomatis mengubah statusnya menjadi **Dibaca**. Ubah menjadi **Selesai ditindaklanjuti** setelah dibalas.
- Balas lewat email atau telepon yang dicantumkan pengirim. Website belum mengirim email otomatis.
- Hanya Admin yang dapat menghapus pesan.

## Log Aktivitas (khusus Admin)
Menu **Log Aktivitas** mencatat siapa menambah, mengubah, atau menghapus data, beserta waktu dan IP. Yang disimpan hanya nama kolom yang berubah, bukan isinya, jadi kata sandi tidak tercatat.

## Statistik kunjungan
Dashboard menampilkan kunjungan hari ini, 7 hari, 30 hari, total, dan halaman terpopuler. Data tidak menyimpan IP atau cookie pelacak, dan kunjungan bot serta halaman admin tidak dihitung.

## Backup database
- Jalankan manual: `php artisan backup:database`. Hasilnya di `storage/app/backups/`, dan 14 backup terbaru dipertahankan (ubah dengan `--keep=30`).
- Backup otomatis harian pukul 02.00 butuh cron di server: `* * * * * php /path/ke/proyek/artisan schedule:run`.
- Untuk MySQL, program `mysqldump` harus terpasang di server.
- **Salin folder backup ke tempat lain** (flashdisk, drive kantor, penyimpanan awan). Backup yang hanya ada di server yang sama tidak menolong saat server rusak.
- Dokumen yang diunggah ada di `storage/app/private/dokumen` dan foto di `storage/app/public`. Keduanya ikut dibackup bila Anda menyalin folder `storage/app`.

## Kebijakan Privasi
Naskah di `/kebijakan-privasi` adalah draf awal. Pimpinan atau bagian hukum instansi sebaiknya meninjau dan menyesuaikannya sebelum situs dipakai resmi.

## Untuk pengembang
- Jalankan tes: `php artisan test` (memakai SQLite di memori, tidak menyentuh database asli).
- GitHub Actions menjalankan tes otomatis di setiap push dan pull request.
- Contoh `.env` produksi ada di `.env.production.example`.
- Header keamanan diatur di `app/Http/Middleware/SecurityHeaders.php` dan `config/security.php`. Jika menambah embed dari domain baru (misalnya video), tambahkan ke `frame-src` atau matikan sementara dengan `CSP_ENABLED=false`.
- Aset Bootstrap, ikon, Leaflet, dan font Poppins dihosting sendiri di `public/vendor`, tidak lagi dari CDN.
