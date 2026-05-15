# 🦊 Wufy Portfolio v1.0
> **Sistem Portofolio Premium dengan Estetika HUD Modern & Teknologi Mutakhir.**

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Svelte](https://img.shields.io/badge/Svelte-5.0-FF3E00?style=for-the-badge&logo=svelte)](https://svelte.dev)
[![Filament](https://img.shields.io/badge/Filament-v5-FBBF24?style=for-the-badge&logo=filament)](https://filamentphp.com)
[![Tailwind](https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)

Wufy adalah platform portofolio personal yang dirancang khusus untuk profesional kreatif yang menginginkan tampilan futuristik bergaya **HUD (Heads-Up Display)**. Dibangun dengan fokus pada kecepatan, SEO, dan kemudahan pengelolaan konten.

---

## 🌟 Mengapa Memilih Wufy? (Untuk Pengguna Biasa)

Wufy bukan sekadar website statis. Ini adalah sistem cerdas yang memungkinkan Anda mengelola identitas digital Anda tanpa harus menyentuh kode program.

### 🎯 Fitur Utama:
- **🚀 Performa Kilat**: Menggunakan teknologi Svelte 5, perpindahan halaman terasa instan tanpa loading yang membosankan.
- **📱 Responsif & Modern**: Tampilan yang menyesuaikan sempurna di HP, Tablet, maupun Laptop dengan desain "Dark Mode" yang elegan.
- **🎨 Kelola Konten Mudah**: Ubah foto profil, daftar projek, riwayat kerja, hingga visi misi melalui panel admin yang sangat intuitif.
- **🔗 Shortlink Generator**: Buat link pendek kustom (misal: `domain.com/cv`) langsung dari dashboard Anda.
- **📊 Statistik Pengunjung**: Pantau berapa banyak orang yang melihat portofolio Anda setiap harinya melalui grafik yang interaktif.
- **🔍 SEO Friendly**: Website Anda lebih mudah ditemukan di Google berkat optimasi otomatis Meta Tags dan Sitemap.

---

## 🛠️ Panduan Untuk Developer

Wufy menggunakan stack teknologi terbaru untuk memastikan skalabilitas dan performa terbaik.

### 🏗️ Stack Teknologi:
- **Core**: Laravel 13 & PHP 8.3+ (Octane Ready with FrankenPHP)
- **Frontend**: Svelte 5 (State-of-the-art Runes reactivity)
- **Engine**: Inertia.js (Monolith feel, SPA performance)
- **Styling**: Tailwind CSS 4.0 (Modern utility-first CSS)
- **Admin Panel**: Filament PHP v5 (Pro-grade dashboard)
- **Image Viewer**: PhotoSwipe v5 (Ultra-smooth image gallery)
- **SEO**: Spatie Sitemap & JSON-LD Schema integration

### 🚀 Cara Instalasi di Lokal:

1. **Persyaratan**: PHP 8.3, Composer, Node.js 22+, MySQL.
2. **Clone Repositori**:
   ```bash
   git clone https://github.com/kucingcoder/wufy.git
   cd wufy
   ```
3. **Instalasi Dependensi**:
   ```bash
   composer install
   npm install
   ```
4. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. **Setup Database**:
   *(Sesuaikan DB_DATABASE di .env terlebih dahulu)*
   ```bash
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```
6. **Jalankan Aplikasi**:
   ```bash
   npm run dev
   php artisan serve
   ```

---

## 🌐 Panduan Deploy ke Hosting (Untuk Pemula)

Jika Anda menggunakan Shared Hosting (cPanel), ikuti langkah sederhana ini:

1. **Build Frontend**: Jalankan `npm run build` di komputer lokal Anda.
2. **Upload Files**: Kompres folder projek Anda (kecuali `node_modules` dan `vendor`) menjadi `.zip`, lalu upload dan ekstrak di root hosting Anda.
3. **Konfigurasi Folder Public**:
   - Pindahkan isi folder `public/` ke folder `public_html/`.
   - Sesuaikan path di `public_html/index.php` agar mengarah ke folder vendor dan bootstrap yang benar.
4. **Setup Database**: Buat database di cPanel, lalu sesuaikan file `.env`.
5. **Symlink Storage (Penting)**: Jika foto tidak muncul, buat file `link.php` di `public_html` berisi:
   ```php
   <?php symlink('/home/username/wufy_source/storage/app/public', '/home/username/public_html/storage'); ?>
   ```
   Akses `domain.com/link.php` sekali, lalu hapus file tersebut.
6. **Akses Admin**:
   - URL: `domainanda.com/admin`
   - Email: `admin@admin.com`
   - Password: `admin`

---

## 📂 Struktur Projek (Advanced)

- `app/Filament/Resources`: Konfigurasi modul admin (Project, Skill, Link, dll).
- `app/Models`: Definisi struktur data dan relasi tabel.
- `database/migrations`: Skema database yang bersih dan terstruktur.
- `resources/js/Pages`: Komponen utama UI menggunakan Svelte 5.
- `resources/js/Components`: UI primitives yang reusable.
- `routes/web.php`: Definisi rute aplikasi.

---

## 🤝 Kontribusi
Kami sangat terbuka bagi siapa saja yang ingin membantu mengembangkan Wufy! Silakan buat **Pull Request** atau laporkan **Issues** jika menemukan bug.

---

## 📜 Lisensi
Projek ini berada di bawah lisensi [MIT](LICENSE).

---
**Dibuat dengan ❤️ oleh [kucingcoder](https://github.com/kucingcoder)**