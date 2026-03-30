# SIMPEL - Sensus Informasi dan Manajemen PELanggan

**SIMPEL** adalah aplikasi manajemen sensus pelanggan modern yang dirancang untuk mempercepat proses pengumpulan data lapangan, validasi teknis, dan pemetaan koordinat lokasi pelanggan PDAM secara akurat dan efisien.

Built with **Laravel 12**, **FilamentPHP 3**, and **Leaflet.js**.

---

## 🌟 Fitur Utama

### 1. Panel Surveyor (Petugas Lapangan)
- **Input Sensus Cerdas**: Pencarian pelanggan terintegrasi dengan pengisian data teknis otomatis (Auto-fill).
- **Map Picker (Leaflet)**: Penentuan koordinat Latitude, Longitude, dan Altitude dengan bantuan tombol GPS Presisi.
- **Media Dokumentasi**: Pengunggahan foto rumah dan meteran sebagai bukti fisik kunjungan.
- **Personal Stats**: Dashboard ringkasan untuk memantau jumlah kiriman yang disetujui atau butuh revisi.

### 2. Panel Analyst (Validator)
- **Review & Validasi**: Antarmuka khusus untuk memeriksa detail sensus, foto, dan skor teknis.
- **Quick Approval**: Tombol "Terima" atau "Minta Revisi" langsung dari daftar tabel atau halaman detail.
- **Scoring System**: Perhitungan poin otomatis berdasarkan jawaban kuesioner untuk menentukan kualitas data teknis.
- **Analytics Dashboard**: Ringkasan statistik performa sensus di seluruh wilayah.

### 3. Arsitektur & Keamanan
- **RBAC (Role-Based Access Control)**: Pemisahan akses ketat antara Admin, Analyst, dan Surveyor.
- **Audit Trails**: Pencatatan aktivitas audit (log) untuk setiap perubahan data sensitif.
- **Responsive Design**: Optimal digunakan pada perangkat mobile (tablet/smartphone) di lapangan.

---

## 🛠️ Cara Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- SQLite (atau database lain pendukung Laravel)

### Langkah-langkah
1. **Clone Repository**
   ```bash
   git clone https://github.com/Fachrimardliana16/SIMPEL-sensus-pelanggan-.git
   cd SIMPEL-sensus-pelanggan-
   ```

2. **Instalasi Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Persiapan Database**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Menjalankan Aplikasi**
   ```bash
   php artisan serve
   ```

---

## 👤 Akun Default (Seeder)
- **Admin**: `admin@surveipro.com` / `password`
- **Analyst**: `analyst@surveipro.com` / `password`
- **Surveyor**: `surveyor@surveipro.com` / `password`

---

## 🏗️ Teknologi yang Digunakan
- **Framework**: [Laravel 12](https://laravel.com)
- **Tampilan**: [FilamentPHP 3](https://filamentphp.com)
- **Database**: SQLite (Development)
- **Maps**: [Leaflet.js](https://leafletjs.com)
- **Ikon**: Heroicons

---

**SIMPEL** - *Solusi Cerdas untuk Manajemen Data Pelanggan yang Lebih Akurat.*
