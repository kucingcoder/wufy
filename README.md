# 🦊 Wufy Portfolio

> **Website portofolio personal bertema kosmik dan anime modern, dilengkapi panel admin mandiri untuk mengelola karya dan profil tanpa perlu coding.**

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js)](https://alpinejs.dev/)
[![Filament](https://img.shields.io/badge/Filament-v5-FBBF24?style=for-the-badge&logo=filament)](https://filamentphp.com)
[![Tailwind](https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)

---

## 📖 Sekilas Tentang Wufy (Untuk Pengguna Awam)

Wufy adalah website portofolio pribadi siap pakai yang dibuat khusus untuk developer, desainer, maupun profesional kreatif lainnya yang ingin memperkenalkan diri dan memamerkan karya di internet.

Berbeda dari website portofolio biasa yang statis dan kaku, Wufy memiliki sistem pengelolaan konten di balik layar. Anda bisa mengganti foto profil, mengunggah file CV, menambah daftar projek baru, hingga memperbarui riwayat pekerjaan kapan saja langsung dari browser tanpa harus menyentuh baris kode program.

---

## 🌌 Seperti Apa Tampilan Web Wufy?

1. **Tema Kosmik Luar Angkasa yang Elegan**
   Website ini mengusung estetika visual anime bernuansa luar angkasa dengan warna dasar biru gelap (*deep night slate*) dan aksen biru langit.
2. **Efek Bintang & Partikel Hidup**
   Latar belakang dilengkapi animasi partikel debu bintang (*stardust*) dan bintang jatuh berkala yang melintas lembut. Efek ini dibuat ringan menggunakan teknologi HTML5 Canvas sehingga tidak membebani performa perangkat dan tetap ramah baterai.
3. **Responsif di Semua Ukuran Layar**
   Tampilan otomatis menyesuaikan secara rapi saat dibuka melalui layar smartphone, tablet, maupun layar laptop dan komputer desktop.
4. **Galeri Foto Interaktif**
   Setiap gambar atau tangkapan layar projek dapat diklik untuk dibuka dalam mode layar penuh (fullscreen) yang jernih dan nyaman dilihat berkat integrasi PhotoSwipe.

---

## 📑 Apa Saja yang Ada di Halaman Depan?

Halaman utama Wufy dirancang terstruktur agar pengunjung maupun calon klien dapat mengenal Anda dengan cepat:

- **Kartu Profil & Biodata**: Menampilkan foto profil, nama lengkap, posisi pekerjaan, lokasi domisili, deskripsi perkenalan singkat, serta tautan cepat ke akun sosial media dan platform profesional (seperti GitHub, LinkedIn, dan Instagram).
- **Layanan / Jasa**: Menjelaskan jenis layanan atau keahlian yang Anda tawarkan beserta deskripsi singkatnya.
- **Portofolio Projek**: Katalog karya dan aplikasi yang pernah Anda buat. Bagian ini dilengkapi filter kategori (Open Source atau Komersial), tahun pembuatan, tautan ke website atau kode sumber, serta galeri gambar pendukung.
- **Keahlian Teknis (Skills)**: Daftar kemampuan teknis dan alat (tools) yang Anda kuasai, tertata rapi berdasarkan kelompok kategori.
- **Pengalaman Kerja (Experiences)**: Rekam jejak karier profesional, nama perusahaan, jabatan, status pekerjaan, serta periode waktu bekerja.
- **Riwayat Pendidikan (Education)**: Riwayat jenjang pendidikan formal maupun non-formal yang pernah ditempuh.
- **Sertifikasi & Lisensi (Certificates)**: Bukti kompetensi dan pencapaian profesional yang pernah Anda raih.
- **Kontak & Unduh CV**: Akses langsung bagi pengunjung untuk menghubungi Anda via WhatsApp atau Email, serta tombol satu klik untuk mengunduh dokumen CV (Curriculum Vitae) berformat PDF.
- **Halaman Legalitas**: Halaman resmi untuk Syarat & Ketentuan (`/terms`), Kebijakan Privasi (`/privacy`), dan Kebijakan Pengembalian Dana (`/refund`).

---

## ✨ Fitur Unggulan untuk Pemilik Website

Mengelola website portofolio kini sangat mudah:

- **Kelola Konten Lewat Panel Admin**: Masuk ke halaman admin (`/admin`) untuk mengedit profil, menambah portofolio baru, mengunggah foto, atau mengganti dokumen CV semudah mengisi formulir di media sosial.
- **Pengaturan Tampilan Fleksibel**: Anda dapat menyembunyikan bagian tertentu dari halaman depan (misalnya sesi Layanan) hanya dengan menggeser sakelar (*toggle*) di menu Pengaturan.
- **Pembuat Tautan Pendek (Shortlink)**: Buat tautan ringkas menggunakan domain Anda sendiri (misalnya `domainanda.com/cv` atau `domainanda.com/wa`) langsung dari dasbor admin.
- **Statistik & Analisis Pengunjung**: Pantau seberapa ramai pengunjung website Anda melalui grafik harian, grafik bulanan, serta pantauan dari mana pengunjung datang (sumber referensi).
- **Cadangkan & Pulihkan Data Sekali Klik**: Amankan seluruh data website Anda (database dan foto-foto yang diunggah) menjadi satu file ZIP langsung dari menu Pengaturan, dan pulihkan data kapan pun dibutuhkan.
- **Otomatis Terindeks Mesin Pencari (SEO)**: Website sudah dilengkapi peta situs otomatis (`/sitemap.xml`), robots.txt, meta tag media sosial, dan format data terstruktur Schema JSON-LD agar nama Anda mudah ditemukan di Google.
- **Bahasa Indonesia Bawaan**: Seluruh label antarmuka halaman admin dan pesan sistem telah diatur menggunakan bahasa Indonesia.

---

## 🛠️ Panduan untuk Developer & Homelab

Bagi Anda yang ingin menginstal, mengembangkan, atau menaruh Wufy di server sendiri:

### Teknologi yang Digunakan
- **Backend**: Laravel 13 & PHP 8.3+ (Mendukung Laravel Octane & FrankenPHP)
- **Frontend**: Laravel Blade & Alpine.js (Melalui CDN, tanpa memerlukan kompilasi Node.js atau Vite)
- **Styling**: Tailwind CSS 4.0
- **Admin Panel**: Filament PHP v5
- **Galeri Gambar**: PhotoSwipe v5
- **SEO**: Spatie Laravel Sitemap & Schema.org JSON-LD

---

### Cara Menjalankan di Komputer Lokal (Development)

1. **Persyaratan**:
   - PHP versi 8.3 atau lebih baru
   - Composer
   - Database MySQL atau MariaDB

2. **Clone Repositori**:
   ```bash
   git clone https://github.com/kucingcoder/wufy.git
   cd wufy
   ```

3. **Pasang Dependensi**:
   ```bash
   composer install
   ```

4. **Siapkan Berkas Konfigurasi (.env)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Pengaturan Database**:
   Sesuaikan informasi database pada file `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), kemudian jalankan:
   ```bash
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka browser Anda dan akses alamat `http://127.0.0.1:8000`.

---

### Cara Pemasangan di Server (VPS / Dedicated Server)

1. Unduh atau clone repositori ini ke folder server Anda (contoh: `/var/www/wufy`).
2. Masuk ke folder projek dan jalankan skrip pengaturan otomatis bawaan Wufy:
   ```bash
   composer setup
   ```
   *(Skrip ini otomatis menginstal dependensi composer, membuat file .env, menghasilkan app key, serta menjalankan migrasi database)*.
3. Buka file `.env` di server, atur `APP_ENV=production`, `APP_DEBUG=false`, dan sesuaikan `APP_URL` dengan domain Anda.
4. Pastikan storage link telah aktif:
   ```bash
   php artisan storage:link
   ```
5. Arahkan direktori *root* web server (Nginx, Apache, atau FrankenPHP) ke folder `public/` milik Wufy.

#### Akun Masuk Admin Bawaan
- **URL Admin**: `domainanda.com/admin`
- **Email**: `admin@admin.com`
- **Password**: `admin`
*(Sangat disarankan segera mengganti email dan password melalui menu Pengaturan setelah pertama kali masuk)*.

---

### Cara Memperbarui Versi Aplikasi (Update)

Jika ada pembaruan kode di repositori GitHub, Anda tidak perlu menjalankan perintah secara manual satu per satu. Cukup jalankan perintah bawaan berikut di server Anda:

```bash
composer redeploy
```

Perintah ini akan secara otomatis:
1. Menyelaraskan kode dengan versi repositori terbaru (`git pull`).
2. Memperbarui dependensi PHP yang diperlukan (`composer install`).
3. Menjalankan penyesuaian skema database (`php artisan migrate`).
4. Membersihkan cache aplikasi (`php artisan optimize:clear`).
5. Menyesuaikan izin akses folder `storage` dan `bootstrap/cache`.

---

## 📂 Struktur Folder Projek

```text
app/
├── Filament/
│   ├── Pages/         # Halaman admin (Kelola Profil, Kebijakan, Pengaturan)
│   ├── Resources/     # Modul data admin (Projek, Keahlian, Pengalaman, Pendidikan, dll)
│   └── Widgets/       # Komponen statistik dan grafik di dasbor admin
├── Models/            # Definisi model data dan relasi database
database/
├── migrations/        # Struktur tabel database
└── seeders/           # Data awal contoh portofolio
resources/
└── views/             # Template tampilan halaman web (Blade & Alpine.js)
routes/
└── web.php            # Daftar rute halaman publik dan sitemap
```

---

## 🤝 Kontribusi

Kontribusi berupa saran, pelaporan kendala (Issues), maupun pengiriman perbaikan (Pull Request) sangat terbuka untuk membantu pengembangan Wufy.

---

## 📜 Lisensi

Projek ini dirilis di bawah lisensi [MIT](LICENSE).

---
**Dibuat oleh [kucingcoder](https://github.com/kucingcoder)**