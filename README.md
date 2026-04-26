# Wufy Portfolio 🚀

Wufy Portfolio adalah sistem portofolio personal premium yang dirancang dengan estetika tinggi dan performa luar biasa. Dibangun menggunakan teknologi modern untuk memberikan kesan profesional dan futuristik bagi penggunanya.

## ✨ Fitur Utama

- **Premium Landing Page**: Desain HUD (Heads-Up Display) modern dengan efek cahaya (*glow*) dan animasi halus.
- **Management Panel**: Panel administrasi lengkap bertenaga **Filament PHP** untuk mengelola profil, pendidikan, pengalaman, keahlian, jasa, dan sertifikat.
- **Dynamic SEO & AI-Friendly**: Dilengkapi dengan Meta Tags dinamis dan JSON-LD (Structured Data) agar mudah terindeks oleh Google dan dapat diekstraksi secara akurat oleh AI.
- **Automatic Slugs**: Pembuatan slug URL otomatis untuk projek, meningkatkan performa SEO.
- **Advanced Image Viewer**: Galeri projek dengan fitur Zoom In/Out, Panning (geser), dan tampilan layar penuh.
- **Custom Error Pages**: Halaman 404 dan 500 yang kustom dan selaras dengan tema utama.
- **Indonesian Language Support**: Seluruh antarmuka manajemen menggunakan Bahasa Indonesia.

## 🛠️ Tech Stack

- **Backend**: [Laravel 13](https://laravel.com) + [FrankenPHP / Octane](https://laravel.com/docs/octane)
- **Frontend**: [Inertia.js](https://inertiajs.com) + [Svelte 5](https://svelte.dev)
- **Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Admin Panel**: [Filament PHP](https://filamentphp.com)
- **Icons**: [Heroicons](https://heroicons.com) & [Simple Icons](https://simpleicons.org)

## 🚀 Instalasi Cepat

1. **Clone repositori**:
   ```bash
   git clone <repository-url>
   cd wufy
   ```

2. **Instal dependensi**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**:
   Sesuaikan konfigurasi database di `.env`, lalu jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Link Storage**:
   ```bash
   php artisan storage:link
   ```

6. **Permissions**:
   Pastikan direktori berikut memiliki izin akses tulis yang benar agar aplikasi dapat berjalan lancar:
   ```bash
   sudo chmod -R 775 storage bootstrap/cache public
   sudo chown -R $USER:www-data storage bootstrap/cache public
   ```

7. **Jalankan Aplikasi**:
   Gunakan Octane (FrankenPHP) untuk performa terbaik:
   ```bash
   php artisan octane:start --watch
   ```
   Dan jalankan Vite di terminal terpisah untuk *assets*:
   ```bash
   npm run dev
   ```

## 🌐 Deployment

### 1. VPS (Rekomendasi)
Gunakan **Laravel Octane** dengan **FrankenPHP** untuk performa maksimal.
- Jalankan `php artisan octane:install`.
- Gunakan **Process Manager** seperti **Supervisor** atau **Systemd** untuk menjaga aplikasi tetap berjalan.
- Gunakan Reverse Proxy seperti **Nginx** atau **Caddy** jika diperlukan.

### 2. Shared Hosting (cPanel)
Untuk hosting tradisional tanpa akses terminal/proses jangka panjang:
1.  **Upload**: Unggah seluruh file ke direktori di luar `public_html`.
2.  **Public Files**: Pindahkan isi folder `public` ke dalam `public_html`.
3.  **Path Adjustment**: Sesuaikan path di `public_html/index.php` untuk mengarah ke lokasi `vendor/autoload.php` dan `bootstrap/app.php` yang baru.
4.  **Symlink**: Buat symlink untuk storage melalui terminal cPanel:
    ```bash
    ln -s /home/user/wufy/storage/app/public /home/user/public_html/storage
    ```
5.  **Optimasi**: Karena Octane tidak bisa berjalan di shared hosting biasa, aplikasi akan berjalan via PHP-FPM standar secara otomatis.

## 🔐 Akun Admin Default

- **URL**: `/admin`
- **Email**: `admin@admin.com`
- **Password**: `admin`

## 📄 Lisensi

Proyek ini dibangun untuk tujuan portofolio personal. Seluruh kode sumber berada di bawah lisensi [MIT](LICENSE).

---
*Dibuat dengan ❤️ oleh Wufy.*
