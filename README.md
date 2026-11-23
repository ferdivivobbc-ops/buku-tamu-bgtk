# Aplikasi Buku Tamu Digital BGTK Sumatera Barat

Aplikasi **Buku Tamu Digital (Kiosk & Back-Office)** untuk **Balai Guru dan Tenaga Kependidikan (BGTK) Provinsi Sumatera Barat**, Kementerian Pendidikan Dasar dan Menengah RI.

Dibangun menggunakan **Laravel 10**, **Tailwind CSS**, **Alpine.js/JavaScript**, **Chart.js**, dan **HTML5 Canvas** (Webcam Snapshot & Digital Signature).

---

## Fitur Utama

### 1. Front-Office / Kiosk Tamu
* **Form Registrasi Mandiri**:
  * QR Code pada halaman kiosk untuk membuka formulir buku tamu melalui ponsel.
  * Input Nama Lengkap (beserta gelar), NIP / NUPTK / NIK, No. WhatsApp, Email, dan Asal Instansi/Sekolah.
  * Pilihan Jabatan (Guru, Kepala Sekolah, Pengawas, Operator, Staf Dinas, Dosen, dll.).
  * Pilihan 19 Kabupaten/Kota di Sumatera Barat.
  * Pilihan Bagian/Tim Kerja yang dituju beserta nama penanggung jawab (PIC) di lingkungan BGTK Sumbar.
  * Kategori keperluan cepat dan rincian agenda kunjungan.
* **Foto Webcam Langsung (Live Snapshot)**:
  * Pengambilan foto langsung dari webcam/kamera tablet kiosk via HTML5 `getUserMedia`.
  * Fallback unggah foto dari berkas / kamera ponsel.
* **Tanda Tangan Digital (Digital Signature Canvas)**:
  * Tanda tangan langsung pada layar sentuh (tablet/smartphone) atau mouse.
* **Tiket Kunjungan Digital (Visitor Pass)**:
  * Penomoran tiket otomatis terstandar: `BGTK-YYYYMMDD-XXXX`.
  * Badge tanda pengenal tamu resmi ber-QR Code untuk verifikasi.
  * Perkiraan waktu dipanggil berdasarkan jumlah antrean di depan, dengan estimasi 15 menit per tamu.
  * Tombol Cetak ID Card / Simpan PDF.
  * Tombol konfirmasi otomatis ke WhatsApp Layanan ULT BGTK.
* **Checkout & Survei Kepuasan (IKM)**:
  * Tamu dapat melakukan checkout saat kunjungan selesai.
  * Penilaian bintang 1-5 (Sangat Puas, Puas, Cukup, Kurang, Sangat Kurang).
  * Masukan, kritik, dan saran demi perbaikan mutu pelayanan publik.
* **Layar Monitor TV Lobby (Kiosk Display)**:
  * Tampilan *high-contrast* untuk layar TV di ruang lobby/resepsionis.
  * Jam digital waktu nyata (WIB), tanggal Masehi & hari.
  * Counter statistik tamu hari ini (Total, Menunggu, Bertemu, Selesai, Rating IKM).
  * Daftar tamu aktif hari ini secara live dengan pembaruan otomatis (auto-refresh 30 detik).
  * Running text warta & pengumuman resmi BGTK Sumbar.

### 2. Panel Admin & Petugas Resepsionis
* **Dashboard Eksekutif**:
  * Metrik total tamu hari ini, bulan ini, total seluruhnya, dan rata-rata IKM.
  * Grafik tren kunjungan 7 hari terakhir (Chart.js Line).
  * Diagram distribusi bagian/tim kerja yang dituju (Chart.js Doughnut).
  * Diagram sebaran tamu per kabupaten/kota di Sumatera Barat (Chart.js Bar).
  * Tabel aksi cepat mutasi status tamu hari ini (*Terima Tamu*, *Selesaikan Kunjungan*).
* **Manajemen Data Buku Tamu**:
  * Pencarian instan (Nama, Nomor Tiket, Instansi, No HP).
  * Filter multi-kriteria: Status, Bagian, Kabupaten/Kota, dan Rentang Tanggal.
  * Detail lengkap tamu (foto asli, tanda tangan, rincian keperluan, kontak WhatsApp langsung).
  * Pembaruan status (Menunggu, Sedang Bertemu, Selesai, Dibatalkan) dan input catatan petugas.
  * **Export Data ke format CSV / Excel** yang terformat rapi dan kompatibel dengan Microsoft Excel.
  * **Cetak Laporan Resmi** lengkap dengan Kop Surat Dinas BGTK Sumbar dan tanda tangan Kepala Balai.
* **Manajemen Bagian & Tim Kerja**:
  * CRUD master data Bagian/Tim Kerja, kode bagian, PIC, lokasi ruangan, dan status aktif.
* **Laporan & Evaluasi IKM**:
  * Laporan rekapitulasi kunjungan harian, bulanan, dan tahunan.
  * Analisis Indeks Kepuasan Masyarakat (IKM) sesuai standar Permenpan RB No. 14 Tahun 2017 (skor konversi 25-100, mutu A/B/C/D).
  * Rekap ulasan, kritik, dan saran pengunjung.
* **Pengaturan Instansi & Pengguna**:
  * Pengaturan profil lembaga (Nama instansi, kementerian, alamat, kontak, nama kepala balai & NIP).
  * Pengaturan running text pengumuman kiosk dan WhatsApp layanan.
  * Manajemen akun petugas resepsionis & administrator.
  * Fitur ubah kata sandi akun.

---

## Akun Login Lokal (Default Seeder)

Akun berikut hanya untuk pengembangan lokal. Jangan gunakan kredensial ini di Production; buat akun Production dengan password yang kuat dan unik.

| Peran | Username | Password | Email |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | `admin@bgtk.sumbar.go.id` |
| **Petugas Resepsionis** | `petugas` | `petugas123` | `resepsionis@bgtk.sumbar.go.id` |

---

## Cara Menjalankan Aplikasi

Pastikan Anda berada di direktori proyek:
```bash
cd C:\Users\hp\.gemini\antigravity\scratch\buku-tamu-bgtk
```

Jalankan server lokal Laravel:
```bash
php artisan serve
```

Aplikasi dapat langsung diakses melalui peramban web:
- **Kiosk Pengunjung (Form Buku Tamu)**: [http://localhost:8000](http://localhost:8000)
- **Checkout & Survei IKM**: [http://localhost:8000/checkout](http://localhost:8000/checkout)
- **Layar Monitor TV Lobby**: [http://localhost:8000/display](http://localhost:8000/display)
- **QR Besar & Unduhan untuk Flashdisk**: [http://localhost:8000/qr-flashdisk](http://localhost:8000/qr-flashdisk)
- **Panel Masuk Petugas & Admin**: [http://localhost:8000/login](http://localhost:8000/login)

Untuk menampilkan QR pada TV tanpa menjalankan aplikasi di TV, buka halaman QR besar di komputer/server, lalu unduh PNG dan salin ke flashdisk. Buka PNG melalui pemutar foto TV. Berkas HTML mandiri juga dapat diunduh, tetapi dukungan membuka HTML dari USB berbeda-beda pada setiap TV.

Sebelum mengunduh, pastikan alamat formulir yang tertera pada halaman QR bisa diakses dari ponsel. Jika server hanya berjalan di jaringan lokal, gunakan alamat IP server (misalnya `http://192.168.1.10:8000`) dan pastikan ponsel berada di jaringan yang sama. Alamat `localhost` atau `127.0.0.1` tidak dapat dipakai ponsel untuk mengakses komputer server.

Untuk memindai QR dari ponsel lain, buka aplikasi menggunakan alamat IP/domain server yang dapat dijangkau ponsel tersebut; alamat `localhost` hanya dapat diakses dari perangkat yang menjalankan server.

## Demo di Vercel (Container Runtime)

Proyek ini menggunakan Laravel/PHP. Vercel dapat menjalankannya melalui Container Runtime (saat ini beta), bukan sebagai static site biasa. Dockerfile di root proyek menyiapkan PHP, Apache, Composer dependencies, dan aset Vite. `.dockerignore` sengaja mengecualikan `.env`, database SQLite lokal, log, foto, dan tanda tangan agar data lokal tidak ikut terunggah.

Langkah ringkas untuk demo:
1. Push proyek ke repository Git yang dapat dihubungkan ke Vercel, lalu import repository tersebut ke Vercel. Pastikan project menggunakan preset **Container** jika tidak terdeteksi otomatis.
2. Buat database PostgreSQL terkelola yang dapat diakses dari Vercel (misalnya Neon). Jangan memakai `database/database.sqlite`; filesystem container tidak persisten dan setiap instance dapat memiliki data lokal yang berbeda.
3. Isi Environment Variables di pengaturan Vercel: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY` (buat dengan `php artisan key:generate --show`), `APP_URL` (URL deployment), `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT=5432`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dan `DB_SSLMODE=require` bila diwajibkan provider. Untuk sesi demo gunakan `SESSION_DRIVER=cookie` dan `CACHE_DRIVER=array`. Jangan simpan `.env` atau kredensial dalam repository.
4. Jalankan migrasi terhadap database demo sekali dengan `php artisan migrate --force` dari lingkungan yang memiliki koneksi ke database dan ekstensi PHP `pdo_pgsql`, lalu deploy ulang bila diperlukan.
5. Uji hanya dengan data fiktif. Jangan menjalankan seeder default di deployment publik: seeder membuat akun admin/petugas dengan kata sandi yang diketahui dan data contoh.

**Batasan penting:** ini hanya konfigurasi awal untuk demo, bukan deployment siap produksi. Foto dan tanda tangan saat ini disimpan melalui disk lokal `public`, yang tidak persisten di Vercel; jangan unggah data/foto pengunjung sungguhan. Untuk penggunaan operasional diperlukan penyimpanan file persisten dan privat, sesi/cache yang sesuai untuk beberapa instance, backup teruji, penggantian kredensial default, serta persetujuan IT/instansi. Vercel Container Runtime berstatus beta dan penggunaan Cloud/hosting mungkin menimbulkan biaya; periksa batasan dan ketentuan paket Vercel sebelum deploy.

---

## Konfigurasi Database

Secara bawaan (*default*), aplikasi telah dikonfigurasi menggunakan **SQLite** (`database/database.sqlite`) sehingga dapat langsung dijalankan tanpa perlu pengaturan server database eksternal.

Jika Anda ingin beralih ke **MySQL** (misal via Laragon atau XAMPP):
1. Buka berkas `.env`
2. Ubah konfigurasi:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=buku_tamu_bgtk
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Buat database baru `buku_tamu_bgtk` di phpMyAdmin / HeidiSQL.
4. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate --seed
   ```
