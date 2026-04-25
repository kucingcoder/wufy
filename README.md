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

6. **Jalankan Aplikasi**:
   Gunakan Octane untuk performa terbaik:
   ```bash
   php artisan octane:start --watch
   ```
   Dan jalankan Vite di terminal terpisah:
   ```bash
   npm run dev
   ```

## 🔐 Akun Admin Default

- **URL**: `/admin`
- **Email**: `admin@admin.com`
- **Password**: `admin`

## 📄 Lisensi

Proyek ini dibangun untuk tujuan portofolio personal. Seluruh kode sumber berada di bawah lisensi [MIT](LICENSE).

---
*Dibuat dengan ❤️ oleh Wufy.*
