<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class PolicyPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profile = Profile::first();

        if (!$profile) {
            return;
        }

        $terms = '
<h2>Syarat &amp; Ketentuan Layanan</h2>
<p>Syarat dan ketentuan ini mengatur penggunaan layanan pengembangan perangkat lunak (software) kustom yang kami berikan. Dengan menggunakan layanan kami, Anda menyetujui ketentuan berikut:</p>
<h3>1. Ruang Lingkup Layanan</h3>
<p>Kami menyediakan layanan pembuatan perangkat lunak kustom termasuk namun tidak terbatas pada sistem manajemen (ERP, CRM), E-Commerce, Point of Sales (POS), sistem reservasi, chatbot, integrasi API pihak ketiga (pembayaran, pengiriman), dan aplikasi web/mobile lainnya sesuai dengan spesifikasi yang disepakati bersama dalam Dokumen Kebutuhan (Requirement Document).</p>
<h3>2. Proses Pengembangan dan Revisi</h3>
<p>Proses pengembangan dilakukan secara bertahap. Klien berhak mendapatkan revisi minor sesuai dengan kesepakatan awal. Perubahan fitur atau penambahan ruang lingkup di luar kesepakatan awal (Change Request) akan dikenakan biaya tambahan dan penyesuaian tenggat waktu.</p>
<h3>3. Pembayaran</h3>
<p>Pembayaran dilakukan secara bertahap (termin) sesuai kesepakatan (misalnya: DP 30%, Termin 40%, Pelunasan 30%). Pengerjaan akan dimulai setelah Down Payment (DP) diterima. Source code dan akses penuh akan diserahkan setelah pelunasan 100%.</p>
<h3>4. Hak Kekayaan Intelektual (HAKI)</h3>
<p>Setelah pelunasan, hak cipta atas perangkat lunak kustom (yang spesifik untuk bisnis klien) menjadi milik klien. Namun, kami berhak mempertahankan hak cipta atas komponen dasar, library, atau modul umum yang telah kami kembangkan sebelumnya dan digunakan dalam proyek tersebut.</p>
<h3>5. Garansi dan Pemeliharaan</h3>
<p>Kami memberikan masa garansi untuk perbaikan bug (bug fixing) dan kesalahan sistem yang tidak sesuai dengan spesifikasi awal (umumnya 1 hingga 3 bulan setelah serah terima, tergantung kesepakatan). Garansi hangus jika terdapat modifikasi kode oleh pihak klien atau pihak ketiga tanpa sepengetahuan kami.</p>
';

        $privacy = '
<h2>Kebijakan Privasi</h2>
<p>Kami sangat menghargai privasi dan kerahasiaan data klien kami. Kebijakan ini menjelaskan bagaimana kami mengelola data Anda:</p>
<h3>1. Pengumpulan Informasi</h3>
<p>Kami mengumpulkan informasi bisnis dan teknis yang diperlukan untuk pengembangan perangkat lunak, termasuk data perusahaan, alur bisnis, kredensial server sementara, dan kontak penanggung jawab proyek.</p>
<h3>2. Penggunaan Data</h3>
<p>Data yang dikumpulkan semata-mata digunakan untuk keperluan analisis, desain, pengembangan sistem, dan komunikasi administratif/keuangan terkait proyek Anda.</p>
<h3>3. Kerahasiaan (Non-Disclosure)</h3>
<p>Kami berkomitmen untuk tidak membagikan ide bisnis, data rahasia, atau informasi pengguna akhir klien kepada pihak ketiga mana pun tanpa izin tertulis. Kami bersedia menandatangani perjanjian kerahasiaan (Non-Disclosure Agreement / NDA) secara terpisah jika diperlukan.</p>
<h3>4. Keamanan Sistem Klien</h3>
<p>Dalam proses integrasi sistem, pembayaran, atau pengiriman, kami menerapkan praktik keamanan standar (enkripsi, tokenisasi) sesuai dengan dokumentasi penyedia layanan (Payment Gateway, Kurir). Namun, keamanan infrastruktur server setelah serah terima (jika di-hosting secara mandiri oleh klien) menjadi tanggung jawab klien.</p>
';

        $refund = '
<h2>Kebijakan Pengembalian Dana (Refund Policy)</h2>
<p>Mengingat sifat layanan pengembangan perangkat lunak kustom yang membutuhkan alokasi sumber daya dan waktu sejak awal, kebijakan pengembalian dana kami adalah sebagai berikut:</p>
<h3>1. Down Payment (DP)</h3>
<p>Down Payment (Uang Muka) yang telah dibayarkan <strong>tidak dapat dikembalikan</strong> (non-refundable) apabila proyek dibatalkan secara sepihak oleh klien setelah proses riset, desain, atau pengkodean telah dimulai.</p>
<h3>2. Pembatalan di Tengah Proyek</h3>
<p>Jika klien memutuskan untuk menghentikan proyek di tengah jalan, klien wajib melunasi biaya proporsional sesuai dengan persentase pekerjaan yang telah diselesaikan hingga tanggal pembatalan tersebut.</p>
<h3>3. Kegagalan Pengiriman (Delivery Failure)</h3>
<p>Pengembalian dana (sebagian atau penuh, tergantung kasus) dapat didiskusikan apabila kami gagal menyelesaikan dan mengirimkan perangkat lunak sesuai dengan spesifikasi utama yang disepakati, tanpa adanya force majeure atau keterlambatan yang disebabkan oleh tidak responsifnya pihak klien.</p>
';

        if (empty($profile->terms_and_conditions)) {
            $profile->terms_and_conditions = $terms;
        }
        if (empty($profile->privacy_policy)) {
            $profile->privacy_policy = $privacy;
        }
        if (empty($profile->refund_policy)) {
            $profile->refund_policy = $refund;
        }

        $profile->save();
    }
}
