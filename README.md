# 🦊 Wufy Portfolio v1.0
> **Sistem Portofolio Premium dengan Estetika HUD Modern & Teknologi Mutakhir.**

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Svelte](https://img.shields.io/badge/Svelte-5.0-FF3E00?style=for-the-badge&logo=svelte)](https://svelte.dev)
[![Filament](https://img.shields.io/badge/Filament-v5-FBBF24?style=for-the-badge&logo=filament)](https://filamentphp.com)
[![Tailwind](https://img.shields.io/badge/Tailwind-4.0-06B6D4?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)

Wufy adalah platform portofolio personal yang dirancang khusus untuk profesional kreatif yang menginginkan tampilan futuristik bergaya **HUD (Heads-Up Display)**. Dibangun dengan fokus pada kecepatan, SEO, dan kemudahan pengelolaan konten.

---

## 🌟 Mengapa Memilih Wufy? (Untuk Pengguna Awam)

Wufy bukan sekadar website statis. Ini adalah sistem cerdas yang memungkinkan Anda mengelola identitas digital Anda tanpa harus menyentuh kode program sama sekali.

### 🎯 Fitur Utama:
- **🚀 Performa Kilat**: Menggunakan teknologi Svelte 5, perpindahan halaman terasa instan tanpa waktu muat (loading) yang membosankan.
- **📱 Responsif & Modern**: Tampilan yang menyesuaikan sempurna di HP, Tablet, maupun Laptop dengan desain "Dark Mode" yang elegan.
- **🎨 Kelola Konten Mudah**: Akses CMS untuk memperbarui seluruh data portofolio Anda secara mandiri:
  - Foto profil & informasi kontak
  - Biodata (About)
  - Daftar Projek (Projects)
  - Riwayat Kerja (Experiences)
  - Jasa / Layanan (Services)
  - Keahlian (Skills)
  - Minat & Hobi (Hobbies)
  - Bahasa (Languages)
  - Halaman Kebijakan (Terms, Privacy, Refund)
- **⚙️ Kustomisasi Tampilan**: Ingin menyembunyikan bagian tertentu dari portofolio Anda? Anda bisa langsung mematikan/menyalakan sesi tertentu (seperti menyembunyikan Jasa, Bahasa, atau Hobi) hanya dengan satu klik *toggle* di menu Pengaturan tanpa mengubah kode.
- **🔗 Shortlink Generator**: Buat tautan pendek khusus (misal: `domain.com/cv`) langsung dari dasbor Anda.
- **📊 Statistik Pengunjung**: Pantau berapa banyak orang yang melihat portofolio Anda setiap harinya melalui grafik interaktif.
- **🔍 SEO Friendly**: Website Anda lebih mudah ditemukan di Google berkat optimasi otomatis pada Meta Tags dan Peta Situs (Sitemap).
- **💾 Backup & Restore Otomatis**: Amankan seluruh data (termasuk database dan gambar yang diunggah) menjadi satu file ZIP, dan pulihkan kapan saja langsung dari menu Pengaturan.
- **🇮🇩 Bahasa Indonesia**: Antarmuka (UI) dan pesan kesalahan telah diatur secara bawaan menggunakan bahasa Indonesia.
- **🏷️ Branding Dinamis**: Nama brand dan judul halaman (page title) website secara otomatis menyesuaikan dengan nama lengkap profil Anda.

---

## 🛠️ Panduan Untuk Developer & Homelab Enthusiasts

Wufy menggunakan stack teknologi terbaru untuk memastikan skalabilitas dan performa terbaik.

### 🏗️ Stack Teknologi:
- **Core**: Laravel 13 & PHP 8.3+ (Octane Ready with FrankenPHP)
- **Frontend**: Svelte 5 (State-of-the-art Runes reactivity)
- **Engine**: Inertia.js (Monolith feel, SPA performance)
- **Styling**: Tailwind CSS 4.0 (Modern utility-first CSS)
- **Admin Panel**: Filament PHP v5 (Pro-grade dashboard)
- **Image Viewer**: PhotoSwipe v5 (Ultra-smooth image gallery)
- **SEO**: Spatie Sitemap & JSON-LD Schema integration

### 🚀 Cara Instalasi di Lokal (Development):

1. **Persyaratan**: PHP 8.3, Composer, Node.js 22+, MySQL/MariaDB.
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
   *(Sesuaikan kredensial DB_DATABASE, DB_USERNAME, DB_PASSWORD di `.env` terlebih dahulu)*
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

## 🌐 Panduan Deployment di Server (VPS / Dedicated / Homelab)

Untuk men-deploy Wufy di server VPS atau mesin Homelab Anda, ikuti langkah-langkah di bawah ini. Pastikan PHP 8.3, Composer, Node.js, dan MySQL telah terinstal.

1. **Siapkan Source Code**: Clone repositori ini di direktori server Anda (misal: `/var/www/wufy`).
2. **Setup Awal**:
   Masuk ke folder projek, lalu jalankan perintah otomatisasi bawaan Wufy:
   ```bash
   composer setup
   ```
   *(Perintah ini akan menjalankan instalasi composer, menyalin .env, mengenerate app key, melakukan migrate & seed database, serta mem-build aset frontend dengan npm)*
3. **Konfigurasi `.env`**: Edit file `.env`, atur `APP_ENV=production`, `APP_DEBUG=false`, dan sesuaikan `APP_URL` dengan domain atau IP Anda.
4. **Link Storage**:
   ```bash
   php artisan storage:link
   ```
5. **Konfigurasi Web Server**:

   <details>
   <summary><b>🗂️ Menggunakan cPanel (Shared Hosting)</b></summary>
   
   Jika Anda menggunakan cPanel biasa tanpa akses terminal root:
   1. Jalankan `npm run build` di komputer lokal Anda terlebih dahulu.
   2. Kompres seluruh folder Wufy (kecuali `node_modules` dan `vendor`) menjadi `.zip`.
   3. Upload dan ekstrak file `.zip` tersebut di file manager hosting Anda, sejajar dengan direktori `public_html` (misal: `/home/username/wufy`).
   4. Pindahkan semua isi dari folder `wufy/public/` ke dalam folder `public_html/`.
   5. Buka `public_html/index.php` dan perbarui path yang menuju ke folder `/vendor/` dan `/bootstrap/` agar mengarah ke direktori `/wufy/` Anda.
   6. Buat database baru di MySQL Database Wizard, lalu perbarui file `.env` di folder `/wufy/`.
   7. **(Penting)** Untuk memunculkan gambar, buat file `link.php` di dalam `public_html` yang berisi:
      `<?php symlink('/home/username/wufy/storage/app/public', '/home/username/public_html/storage'); ?>`
      Akses `domainanda.com/link.php` sekali di browser Anda, lalu hapus file tersebut.
   </details>

   <details>
   <summary><b>🔥 Menggunakan Nginx (Direkomendasikan)</b></summary>
   
   Buat konfigurasi virtual host di `/etc/nginx/sites-available/wufy.conf`:
   ```nginx
   server {
       listen 80;
       server_name domainanda.com;
       root /var/www/wufy/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;

       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
   Aktifkan konfigurasi dan restart Nginx:
   ```bash
   ln -s /etc/nginx/sites-available/wufy.conf /etc/nginx/sites-enabled/
   systemctl reload nginx
   ```
   </details>

   <details>
   <summary><b>🛠️ Menggunakan Apache</b></summary>
   
   Buat konfigurasi virtual host di `/etc/apache2/sites-available/wufy.conf`:
   ```apache
   <VirtualHost *:80>
       ServerName domainanda.com
       DocumentRoot /var/www/wufy/public

       <Directory /var/www/wufy/public>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>

       ErrorLog ${APACHE_LOG_DIR}/wufy-error.log
       CustomLog ${APACHE_LOG_DIR}/wufy-access.log combined
   </VirtualHost>
   ```
   Aktifkan modul rewrite dan site, lalu restart Apache:
   ```bash
   a2enmod rewrite
   a2ensite wufy.conf
   systemctl restart apache2
   ```
   </details>

6. **Akses Admin**:
   - URL: `domainanda.com/admin`
   - Email: `admin@admin.com`
   - Password: `admin`

---

## 🔄 Cara Melakukan Update

Untuk memperbarui aplikasi saat ada versi terbaru dari repositori, Anda tidak perlu repot menjalankan perintah secara manual satu per satu. Wufy menyediakan skrip composer bawaan.

Cukup jalankan perintah berikut di dalam direktori projek pada server/homelab Anda:
```bash
composer redeploy
```

**Apa yang dilakukan perintah ini?**
1. Mereset kode lokal ke versi terbaru (Git).
2. Menarik pembaruan terbaru dari repositori (`git pull`).
3. Menjalankan migrasi database jika ada skema tabel baru (`php artisan migrate --force`).
4. Mem-build ulang aset frontend (Svelte/Tailwind) secara otomatis menggunakan `bun run build`.
5. Membersihkan cache Laravel (`php artisan optimize:clear`).
6. Mengatur ulang hak akses folder (Permission) di direktori `storage`, `bootstrap`, dan `public`.

---

## 📂 Struktur Projek (Advanced)

- `app/Filament/Pages`: Halaman kustom admin, termasuk integrasi fitur Backup & Restore native.
- `app/Filament/Resources`: Konfigurasi modul admin (Project, Skill, Link, dll).
- `app/Models`: Definisi struktur data dan relasi tabel.
- `database/migrations`: Skema database yang bersih dan terstruktur.
- `resources/js/Pages`: Komponen utama UI frontend menggunakan Svelte 5.
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