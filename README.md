# Wufy Portfolio v1.0 🚀

Wufy Portfolio adalah sistem portofolio personal premium yang menggabungkan estetika futuristik dengan teknologi backend yang sangat efisien. Dirancang untuk developer, desainer, atau profesional kreatif yang ingin tampil menonjol dengan antarmuka HUD (Heads-Up Display) yang modern.

---

## ✨ Tentang Projek Ini

Wufy bukan sekadar template portofolio biasa. Ini adalah aplikasi web lengkap yang dibangun dengan arsitektur modern (Monolith-Inertia) yang memisahkan logika backend dari tampilan frontend tanpa mengorbankan kecepatan pengembangan.

### 🌟 Kelebihan Utama:
- **Estetika HUD Modern**: Menggunakan desain bertema gelap dengan aksen cahaya neon (Sky Blue & Indigo) yang memberikan kesan "High-Tech".
- **Instant Interaction**: Dibangun dengan **Svelte 5** (State-of-the-art reactivity) untuk transisi antar halaman yang sangat mulus tanpa refresh.
- **Manajemen Konten Tanpa Kode**: Panel admin **Filament v3** yang intuitif, memungkinkan Anda mengubah seluruh isi website tanpa menyentuh kode.
- **SEO Optimized**: Mendukung SSR (Server-Side Rendering) via Inertia, Meta Tags dinamis, dan skema JSON-LD untuk peringkat pencarian yang lebih baik.

### 🛠️ Fitur - Fitur:
- **Daftar Projek dengan Pagination**: Mendukung hingga puluhan projek tanpa memperlambat halaman.
- **Pencarian Projek Real-time**: Cari portofolio Anda secara instan.
- **Manajemen Edukasi & Pengalaman**: Tampilan kronologis yang rapi.
- **Keahlian & Jasa**: Klasifikasi keahlian berdasarkan kategori dan penawaran jasa dengan harga transparan.
- **Visi & Misi**: Tampilan kutipan minimalis yang elegan.
- **Statistik Pengunjung**: Dashboard admin untuk memantau trafik pengunjung unik dan sumber referal.
- **Dark Mode Native**: Desain yang nyaman di mata sejak awal.

---

## 💻 Panduan Menjalankan di Lokal (Developer)

Ikuti langkah berikut untuk menyiapkan lingkungan pengembangan:

### Persyaratan:
- PHP >= 8.3
- Node.js >= 22
- Composer
- Database (MySQL/PostgreSQL/SQLite)

### Langkah Instalasi:
1. **Clone & Install**:
   ```bash
   git clone https://github.com/kucingcoder/wufy.git
   cd wufy
   composer install
   npm install
   ```
2. **Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. **Database Setup**:
   Buat database baru, sesuaikan `.env`, lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Ini akan membuat data dummy dan akun admin otomatis)*
4. **Storage Link**:
   ```bash
   php artisan storage:link
   ```
5. **Jalankan Server**:
   Buka dua terminal:
   - Terminal 1 (Backend): `php artisan serve` (atau `php artisan octane:start` jika menggunakan FrankenPHP)
   - Terminal 2 (Frontend): `npm run dev`

---

## 🌐 Cara Deploy ke cPanel Shared Hosting (Untuk Pemula)

Shared hosting biasanya tidak mendukung perintah terminal yang kompleks, jadi ikuti langkah "Manual" ini:

1. **Persiapan di Lokal**:
   - Jalankan `npm run build` di komputer Anda.
   - Kompres seluruh folder projek Anda menjadi file `.zip` (Kecuali folder `node_modules` dan `storage/framework/cache`).
2. **Upload ke cPanel**:
   - Masuk ke **File Manager**.
   - Upload file `.zip` ke direktori root (di atas `public_html`, misal: `/home/username/wufy_source`).
   - Ekstrak file tersebut.
3. **Pengaturan Folder Public**:
   - Masuk ke folder `/home/username/wufy_source/public`.
   - Pindahkan seluruh isinya ke folder `public_html`.
4. **Konfigurasi `index.php`**:
   - Edit file `public_html/index.php`.
   - Cari baris `require __DIR__.'/../vendor/autoload.php';` dan `bootstrap/app.php`.
   - Ubah path-nya agar mengarah ke folder sumber Anda, misal:
     ```php
     require __DIR__.'/../wufy_source/vendor/autoload.php';
     $app = require_once __DIR__.'/../wufy_source/bootstrap/app.php';
     ```
5. **Setup Database**:
   - Buat database, user, dan password di menu **MySQL Databases** cPanel.
   - Edit file `.env` di folder sumber (`wufy_source`) dan masukkan detail database tersebut.
   - Ubah `APP_ENV=production` dan `APP_DEBUG=false`.
6. **Symlink Storage (Penting)**:
   - Agar foto projek muncul, buat symlink. Jika tidak ada akses SSH, buat file PHP baru (misal `link.php`) di `public_html` berisi:
     ```php
     <?php symlink('/home/username/wufy_source/storage/app/public', '/home/username/public_html/storage'); ?>
     ```
   - Akses `namadomain.com/link.php` sekali, lalu hapus file tersebut.

---

## 📚 Dokumentasi Developer Tingkat Lanjut

### Struktur Direktori Penting:
- **`app/Filament/Resources`**: Tempat konfigurasi dashboard admin. Anda bisa mengubah form input, tabel, dan urutan menu di sini.
- **`app/Models`**: Definisi struktur tabel database (Project, Skill, Experience, dll).
- **`resources/js/Pages`**: Komponen utama Svelte. `Welcome.svelte` adalah halaman landing utama.
- **`resources/js/Components`**: Komponen UI kecil yang bisa digunakan berulang kali.
- **`database/migrations`**: Definisi skema database yang sudah dikonsolidasi (Clean Migrations).

### Data Flow (Inertia.js):
Aplikasi ini tidak menggunakan API tradisional (REST/GraphQL). Controller di Laravel mengirim data langsung ke Svelte sebagai `props`. 
- Jika ingin menambah data ke landing page, edit `app/Http/Controllers/WelcomeController.php`.
- Data tersebut akan tersedia di `Welcome.svelte` dalam variabel `$props()`.

### Kustomisasi Desain:
- Warna utama dikontrol melalui utility class Tailwind di `Welcome.svelte`.
- Gunakan variabel CSS di bagian `<style>` Svelte untuk mengubah efek glow atau animasi khusus.

---

## 🔐 Akses Admin Default
- **URL**: `yourdomain.com/admin`
- **Email**: `admin@admin.com`
- **Password**: `admin`

---