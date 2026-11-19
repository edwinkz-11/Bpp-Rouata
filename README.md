# Website BPP Pertanian Routa (Laravel)

**Cara tercepat:** `./setup.sh` (butuh PHP 8.2+, Composer, MySQL) atau `docker compose up --build` (butuh Docker saja). Keduanya membuat proyek, mengatur .env, migrasi, dan akun admin otomatis.

Sistem informasi pertanian untuk **BPP Pertanian Routa (Balai Penyuluh Pertanian Kecamatan Routa)**:

1. **Website publik**: Home, Profil Kecamatan, Kegiatan Pertanian (filter, pencarian, pagination, lightbox), Potensi Pertanian, Peta Wilayah (Leaflet + OpenStreetMap), Galeri, Tentang BPP.
2. **Dashboard admin** (`/admin`): kelola profil, desa, kegiatan, galeri, komoditas, titik peta, jalur, penyuluh, pengaturan website, dan pengguna.

Teknologi: Laravel 11/12 (PHP 8.2+), Blade, Bootstrap 5.3, JavaScript murni, MySQL, Leaflet.js, OpenStreetMap. Tanpa React/Vue.

> Paket ini adalah **kode aplikasi** (app, database, routes, views, public, lang) yang ditempel di atas proyek Laravel baru. Folder `vendor/` dan kerangka Laravel diunduh Composer saat instalasi.

---

## 1. Kebutuhan

- PHP 8.2 atau lebih baru (ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `gd` atau `imagick`, `json`)
- Composer 2
- MySQL 8 / MariaDB 10.6+
- Koneksi internet pada peramban pengunjung (memuat Bootstrap, Leaflet, dan ubin peta OpenStreetMap dari CDN)

## 2. Instalasi

```bash
# a. Buat proyek Laravel baru
composer create-project laravel/laravel bpp-routa
cd bpp-routa

# b. Tempel paket ini ke proyek (dari folder paket ini)
#    Cara cepat:  ./install.sh /path/ke/bpp-routa
#    Cara manual: salin isi folder app, database, lang, public, resources, routes ke proyek (timpa jika ditanya)

# c. Buat database
mysql -u root -p < database/sql/00_create_database.sql
#    (isinya: CREATE DATABASE bpp_pertanian_routa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;)
```

> **Penting:** paket ini menimpa file `database/migrations/0001_01_01_000000_create_users_table.php` bawaan Laravel (menambah kolom `role`) dan `database/seeders/DatabaseSeeder.php`, `app/Providers/AppServiceProvider.php`, `routes/web.php`.

## 3. Konfigurasi `.env`

Salin `.env.example` menjadi `.env`, lalu ubah/tambahkan:

```dotenv
APP_NAME="BPP Pertanian Routa"
APP_ENV=local
APP_DEBUG=true            # ubah ke false di server produksi
APP_URL=http://127.0.0.1:8000
APP_LOCALE=id
APP_FALLBACK_LOCALE=en

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bpp_pertanian_routa
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
FILESYSTEM_DISK=public

# Akun admin pertama (dipakai seeder)
ADMIN_NAME="Administrator BPP Routa"
ADMIN_EMAIL=admin@contoh.go.id
ADMIN_PASSWORD=GantiDenganSandiKuat123
```

Jika `ADMIN_PASSWORD` dikosongkan, seeder membuat kata sandi acak dan menampilkannya **sekali** di terminal.

## 4. Migrasi, akun admin, dan storage

```bash
php artisan key:generate
php artisan migrate --seed     # membuat semua tabel + akun admin + 7 kategori kegiatan + data dasar
php artisan storage:link       # agar foto yang diunggah bisa diakses publik
php artisan serve
```

- Website publik: <http://127.0.0.1:8000>
- Login admin: <http://127.0.0.1:8000/admin/login>

Jalankan `migrate --seed` **sebelum** `php artisan config:cache` (seeder membaca `.env`).

### Batas unggah foto (php.ini)

Aplikasi menerima foto sampai 4 MB per file (JPG, JPEG, PNG, WEBP). Bawaan PHP biasanya hanya 2 MB, jadi naikkan di `php.ini`:

```ini
upload_max_filesize = 8M
post_max_size = 64M
max_file_uploads = 30
```

## 5. Hak akses

| Fitur | Admin | Editor |
|---|---|---|
| Kelola profil, kegiatan, galeri, komoditas, titik peta, jalur, pengaturan | ✔ | ✔ |
| Hapus kegiatan / lokasi / jalur | semua | hanya yang ia buat sendiri |
| Hapus desa, kategori, penyuluh | ✔ | ✘ (boleh tambah/edit) |
| Manajemen pengguna | ✔ | ✘ |

Tidak ada registrasi publik. Akun baru dibuat Admin di menu **Manajemen Pengguna**. Login dibatasi 5 percobaan/menit, kata sandi di-hash (bcrypt), semua form dilindungi CSRF, dan akses dashboard dijaga middleware `auth` + `EnsureRole`.

## 6. Alur kerja petugas

1. **Pengaturan Website**: slogan, foto hero, tautan media sosial (Instagram `bpp.routa` sudah terisi).
2. **Profil BPP** dan **Profil Kecamatan**: isi alamat, telepon, email, visi-misi, sejarah, dll. Selama kosong, halaman publik menampilkan catatan "belum diisi" (tidak ada data karangan).
3. **Data Desa** → **Potensi Pertanian** → **Kegiatan Pertanian** (unggah foto sampul + banyak foto dokumentasi) → **Galeri Dokumentasi** (unggah massal, ubah keterangan).
4. **Peta Wilayah** dan **Data Jalur** (lihat bagian berikut).

### Pemetaan bertahap (jalur yang belum terdata)

- **Titik lokasi**: klik peta, geser penanda, isi koordinat manual, atau tombol **GPS** saat berada di lokasi (HP harus membuka situs lewat HTTPS agar GPS aktif).
- **Jalur**: klik peta untuk menambah titik berurutan, geser titik untuk memperbaiki, klik kanan titik untuk menghapus. Bisa juga **menempel koordinat** (`lat, lng` per baris) atau **GeoJSON LineString** hasil rekam GPS. Berjalan kaki/berkendara sambil menambah titik GPS dari HP juga bisa.
- **Status** setiap titik/jalur: `Draft` (tidak pernah tampil publik), `Belum Terverifikasi` (tampil dengan label peringatan; jalur putus-putus), `Terverifikasi`. Satu lagi syarat tampil: kotak **Tampilkan di peta publik** dicentang.
- **Batas wilayah**: tempel GeoJSON batas kecamatan dari sumber resmi di **Pengaturan Website**. Kosong = tidak ditampilkan.
- Pusat peta bawaan hanya sudut pandang awal kawasan Sulawesi Tenggara (bukan data lokasi). Setelah ada titik, peta otomatis menyesuaikan, atau atur pusat peta di Pengaturan Website.

## 7. Struktur proyek

```
app/
  Http/Controllers/Auth/LoginController.php
  Http/Controllers/Web/*        # halaman publik
  Http/Controllers/Admin/*      # dashboard & CRUD
  Http/Middleware/EnsureRole.php
  Http/Requests/*               # validasi per fitur
  Models/*  (+ Concerns/DeletesImages)
  Services/ImageService.php     # upload, nama unik, hapus file
  Providers/AppServiceProvider.php   # Gate + data bersama view
database/migrations, seeders, sql/
lang/id/validation.php          # pesan validasi bahasa Indonesia
public/css|js|images            # site.css, admin.css, public-map.js, admin-map.js, ...
resources/views/{layouts,partials,components,web,admin}
routes/web.php
```

## 8. Skema database

Mengikuti SQL yang Anda berikan (users, password_reset_tokens, sessions, profiles, villages, activity_categories, activities, activity_photos, commodities, locations, routes) dengan foreign key, index, dan relasi Eloquent. **Tambahan** agar semua fitur di brief berjalan:

- `profiles`: kolom `type` (`bpp`/`kecamatan`), `tasks_functions`, `geography`, `environment`, `society`, `agriculture_potential`
- `commodities`: `location_note`, `extra_info`
- `locations`: `extra_info`
- tabel baru `extension_workers` (data penyuluh) dan `settings` (slogan, media sosial, foto hero, pusat peta, batas wilayah)

Tabel `routes` dimodelkan sebagai `App\Models\MapRoute` agar tidak bentrok dengan facade `Route`.

## 9. Keamanan upload foto

Validasi format `jpg, jpeg, png, webp` dan ukuran maks 4 MB; ekstensi ditentukan dari isi file (bukan nama file); nama file diganti UUID; disimpan di `storage/app/public/{kegiatan,galeri,komoditas,lokasi,...}`; path disimpan di database; file otomatis dihapus saat data dihapus atau fotonya diganti. SVG tidak diterima untuk unggahan.

## 10. Produksi

- `APP_DEBUG=false`, `APP_ENV=production`, HTTPS aktif.
- `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- Arahkan document root server ke folder `public/`.
- Cadangkan database dan folder `storage/app/public/` secara berkala.

## 11. Soal foto dan konten

Paket ini **tidak menyertakan foto unduhan dari internet** (hak cipta milik pemotret/akun Instagram). Sebelum ada foto, halaman memakai ilustrasi netral (`public/images/hero-default.svg`, `placeholder.svg`). Foto resmi BPP (mis. dari Instagram `@bpp.routa`) diunggah petugas lewat dashboard. Logo bawaan: `public/images/logo-kementan.png` (dapat diganti di Profil BPP).

## Struktur organisasi, video tim, dan data penyuluh
- Gambar struktur (`public/images/struktur-bpp-routa.jpg`) tampil di halaman **Tentang BPP**; klik untuk memperbesar.
- Video tim (`public/videos/tim-bpp-routa.mp4`, dibuat dari 7 kartu perkenalan) ada di Home dan Tentang BPP. Video otomatis diputar saat terlihat saat di-scroll dan dijeda saat keluar layar. Video dibisukan karena browser hanya mengizinkan autoplay tanpa suara (ada tombol suara).
- Admin dapat mengganti gambar struktur dan video di **Pengaturan Website** (video MP4/WebM maks. 50 MB).
- `WorkerSeeder` mengisi 7 penyuluh (nama, jabatan, wilayah, foto) dari bagan struktur. Edit di menu Penyuluh jika ada yang kurang tepat.

## Berita dari situs Kementan
- Menu **Berita** (`/berita`) dan bagian "Kabar Terbaru Kementerian Pertanian" di Home mengambil daftar berita dari **pertanian.go.id**.
- Yang ditampilkan hanya judul, tanggal, gambar sampul, dan tautan. Isi berita dibaca di situs Kementan (sumber dicantumkan di halaman).
- Hasil di-cache 3 jam (`KEMENTAN_NEWS_CACHE_MINUTES`). Jika situs Kementan tidak bisa diakses, tampil cache terakhir, atau tombol menuju situs Kementan.
- Matikan dengan `KEMENTAN_NEWS_ENABLED=false` di `.env`. Server hosting harus bisa mengakses internet keluar (HTTPS).
- Kode: `app/Services/KementanNewsService.php`, pengaturan di `config/kementan.php`.
- Jika Kementan mengubah tampilan situsnya dan berita berhenti muncul, perbaiki fungsi `parse()` di service tersebut.

## Isi awal data Kecamatan Routa
```
php artisan db:seed --class=RoutaContentSeeder
```
Mengisi profil kecamatan (hanya kolom yang masih kosong), daftar desa/kelurahan tanpa koordinat, dan komoditas padi sawah, lengkap dengan sumbernya. Aman dijalankan ulang. Cocokkan angka dengan publikasi BPS "Kecamatan Routa Dalam Angka".

## Foto kegiatan (KegiatanFotoSeeder)

21 foto dokumentasi dimasukkan sebagai 9 album kegiatan (7 terbit, 2 draft yang perlu dicek):

```bash
php artisan storage:link
php artisan db:seed --class=KegiatanFotoSeeder
```

Aman dijalankan ulang (album berjudul sama dilewati). Album draft tidak tampil publik; terbitkan lewat Admin > Kegiatan setelah lokasi dipastikan.
