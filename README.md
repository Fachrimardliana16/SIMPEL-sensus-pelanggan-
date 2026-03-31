# SIMPEL — Sensus Informasi & Manajemen Pelanggan

<p align="center">
  <strong>Aplikasi manajemen sensus pelanggan PDAM modern — pengumpulan data lapangan, validasi teknis, dan pemetaan geospasial dalam satu platform terpadu.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/FilamentPHP-3-FDAE4B?style=flat-square&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind%20CSS-4-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white" />
  <img src="https://img.shields.io/badge/Leaflet.js-1.9.4-199900?style=flat-square&logo=leaflet&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/Version-1.0.2--Stable-blue?style=flat-square" />
</p>

---

## 📋 Daftar Isi

- [Deskripsi](#-deskripsi)
- [Arsitektur Aplikasi](#-arsitektur-aplikasi)
- [Fitur Lengkap](#-fitur-lengkap)
- [Tech Stack](#-tech-stack)
- [Instalasi](#-instalasi)
- [Akun Default](#-akun-default)
- [Struktur Database](#-struktur-database)
- [API Endpoints](#-api-endpoints)
- [Lisensi](#-lisensi)

---

## 📖 Deskripsi

**SIMPEL** adalah aplikasi enterprise-grade untuk mengelola sensus pelanggan PDAM Tirta Perwira. Dirancang untuk wilayah **Purbalingga, Jawa Tengah**, aplikasi ini mencakup seluruh siklus kerja sensus: dari pendataan lapangan oleh surveyor, validasi data oleh analyst, hingga monitoring real-time oleh administrator.

Sistem ini menggunakan arsitektur **multi-panel** dengan pemisahan akses berbasis peran (RBAC) yang ketat, audit trail lengkap, dan visualisasi data geospasial interaktif.

---

## 🏗 Arsitektur Aplikasi

```
┌─────────────────────────────────────────────────────────────────┐
│                     PUBLIC MONITORING                            │
│               http://domain.com/ (welcome.blade.php)            │
│     Live Clock · Peta Sensus · Chart Tren · Statistik Real-time │
└──────────────────────────┬──────────────────────────────────────┘
                           │
     ┌─────────────────────┼─────────────────────┐
     │                     │                     │
     ▼                     ▼                     ▼
┌─────────┐         ┌───────────┐         ┌───────────┐
│  ADMIN  │         │  ANALYST  │         │ SURVEYOR  │
│  /admin │         │  /analyst │         │ /surveyor │
├─────────┤         ├───────────┤         ├───────────┤
│Pelanggan│         │Review     │         │Input      │
│Users    │         │Sensus     │         │Sensus     │
│Pertanyaan│        │Approve/   │         │GPS Coord  │
│Tarif    │         │Reject     │         │Photo      │
│Unit     │         │Peta Valid │         │Kuesioner  │
│Status   │         │Print PDF  │         │Print PDF  │
│Roles    │         │           │         │           │
│Dashboard│         │Dashboard  │         │Dashboard  │
└─────────┘         └───────────┘         └───────────┘
```

---

## ✨ Fitur Lengkap

### 🌐 Halaman Publik (Monitoring Real-time)
| Fitur | Deskripsi |
|-------|-----------|
| **Live Clock** | Jam real-time format Indonesia (hari, tanggal, WIB) |
| **Statistik Capaian** | Progress bar sensus: capaian %, pelanggan aktif, tutup sementara, bongkar |
| **Status Grid** | Target sensus, data valid, review/pending, sisa survey |
| **Tren Performa** | Indikator naik/turun dengan persentase dan top performer |
| **Kualitas Data** | Rata-rata poin kualitas data sensus |
| **Peta Interaktif** | Leaflet.js — persebaran titik sensus dengan warna status (hijau/kuning/merah) |
| **Grafik Tren** | Chart.js bar chart — tren input harian/bulanan dengan filter toggle |
| **Filter Dinamis** | Toggle HARIAN/BULANAN — semua widget otomatis update via AJAX |

---

### 👑 Panel Administrator (`/admin`)
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik akumulasi, tren input 7 hari, verifikasi analyst, progres doughnut chart, log aktivitas, input terbaru |
| **Kelola Pelanggan** | CRUD data pelanggan PDAM — filter tab dengan badge count, widget statistik (total, valid, pending, cancel) |
| **Kelola Users** | Manajemen pengguna dengan assignment role (Admin, Analyst, Surveyor) |
| **Kelola Pertanyaan** | Builder kuesioner sensus — tipe: text, radio, checkbox, select, textarea |
| **Kelola Tarif** | Master data tarif pelanggan PDAM |
| **Kelola Unit** | Master data unit/wilayah kerja |
| **Kelola Status** | Master data status pelanggan |
| **Role & Permission** | Manajemen RBAC via Spatie Laravel Permission |
| **Export PDF** | Laporan sensus dalam format PDF (landscape, A4) via DomPDF |

**Widget Dashboard Admin:**
- `SurveyProgressDoughnut` — Progres sensus keseluruhan (doughnut chart)
- `AdminInputTrendChart` — Tren input sensus 7 hari terakhir (bar chart)
- `AdminVerificationChart` — Aktivitas verifikasi analyst 7 hari (bar chart)
- `ActivityLogWidget` — Log aktivitas sistem terbaru
- `LatestSensusWidget` — Input sensus terbaru (tabel)

---

### 🔍 Panel Analyst (`/analyst`)
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik overview, distribusi status sensus (pie chart), input terbaru, kinerja karyawan, peta persebaran |
| **Quick Access** | Tombol cepat: Review Pending, Profil Saya — sejajar horizontal |
| **Review Sensus** | List view dengan filter tab (badge count per status), widget statistik |
| **View Detail** | Layout split kiri-kanan: identitas pelanggan, meter & tarif, lokasi sensus (embed Google Maps), validasi, kuesioner Q&A (kiri) + dokumentasi foto (kanan) |
| **Approve / Reject** | Tombol "Approve" dan "Ulang" dengan form catatan reviewer |
| **Cetak PDF** | Print single-record sensus ke PDF (portrait, A4) |
| **Peta Geospasial** | Leaflet.js — persebaran data sensus valid, centered di Purbalingga |

**Widget Dashboard Analyst:**
- `AnalystStats` — Total sensus, pending, valid, revisi
- `CensusStatusPieChart` — Distribusi status sensus (pie chart)
- `LatestSensusWidget` — Input sensus terbaru (tabel)
- `SurveyorPerformanceChart` — Kinerja karyawan (bar chart)
- `AnalystMapWidget` — Peta persebaran sensus valid (Leaflet.js)
- `AnalystQuickReviewWidget` — Tombol cepat review pending
- `AnalystProfileWidget` — Tombol cepat profil

---

### 📝 Panel Surveyor (`/surveyor`)
| Fitur | Deskripsi |
|-------|-----------|
| **Dashboard** | Statistik personal, tren kiriman (line chart), sensus terbaru, peta kiriman |
| **Quick Access** | Tombol cepat: Input Sensus Baru, Profil Saya — sejajar horizontal |
| **Input Sensus** | Form multi-step: cari pelanggan → auto-fill data PDAM → GPS koordinat → foto → kuesioner → submit |
| **Map Picker** | Peta Leaflet dengan tombol GPS presisi (lat, long, alt) |
| **Dokumentasi Foto** | Upload foto rumah dan foto meteran sebagai bukti kunjungan |
| **Kuesioner Dinamis** | Pertanyaan diambil dari database, mendukung: text, radio, checkbox, select, textarea |
| **Scoring Otomatis** | Kalkulasi poin kualitas data berdasarkan jawaban kuesioner |
| **View Detail** | Layout split identik dengan panel Analyst (konsisten) |
| **Cetak PDF** | Print single-record sensus ke PDF |
| **Peta Kiriman** | Leaflet.js — pin berwarna per status: 🟢 Valid, 🟠 Pending, 🔴 Revisi |
| **Filter Peta** | Toggle filter interaktif — klik tombol Valid/Pending/Revisi untuk show/hide marker |

**Widget Dashboard Surveyor:**
- `SurveyorStats` — Total sensus, menunggu, disetujui, revisi
- `MySubmissionTrendChart` — Tren kiriman 7 hari / 6 bulan (line chart dengan filter)
- `RecentSensusTable` — Sensus terbaru saya (tabel)
- `SurveyorMapWidget` — Peta kiriman sensus dengan filter status interaktif
- `SurveyorQuickInputWidget` — Tombol cepat input sensus
- `SurveyorProfileWidget` — Tombol cepat profil

---

### 🔐 Keamanan & Audit
| Fitur | Deskripsi |
|-------|-----------|
| **RBAC** | Role-Based Access Control via Spatie Laravel Permission (Super Admin, Admin, Analyst, Surveyor) |
| **Panel Isolation** | Setiap role hanya bisa mengakses panelnya sendiri |
| **Audit Trail** | Pencatatan aktivitas via Spatie Activity Log — siapa, kapan, apa yang berubah |
| **UUID Primary Keys** | Semua model menggunakan UUID untuk kompatibilitas cross-database |
| **Soft Deletes** | Data tidak pernah benar-benar dihapus, hanya di-soft-delete |
| **Gate Authorization** | Cetak PDF diproteksi — analyst bisa cetak semua, surveyor hanya miliknya |

---

### 📄 Export & Reporting
| Fitur | Deskripsi |
|-------|-----------|
| **Laporan Sensus (Batch)** | PDF landscape A4 — filter berdasarkan tanggal dan surveyor |
| **Cetak Sensus (Single)** | PDF portrait A4 — detail lengkap satu record sensus |
| **Route** | `GET /export/sensus-pdf` (batch), `GET /export/sensus-print/{id}` (single) |
| **Library** | Barryvdh DomPDF |

---

## 🛠 Tech Stack

| Layer | Teknologi | Versi |
|-------|-----------|-------|
| **Backend** | Laravel | 12.x |
| **Admin UI** | FilamentPHP | 3.x |
| **CSS** | Tailwind CSS | 4.x |
| **Database** | SQLite (dev) / PostgreSQL (prod) | — |
| **Maps** | Leaflet.js | 1.9.4 |
| **Charts** | Chart.js (welcome) / Filament Chart Widget | — |
| **PDF** | Barryvdh DomPDF | 3.x |
| **Excel** | Maatwebsite Excel | 3.x |
| **RBAC** | Spatie Laravel Permission | 7.x |
| **Audit Log** | Spatie Laravel Activity Log | 5.x |
| **Queue** | Laravel Horizon + Redis (Predis) | — |
| **Icons** | Heroicons | — |
| **Font** | Plus Jakarta Sans (welcome) / Inter (panels) | — |

---

## 🚀 Instalasi

### Prasyarat
- PHP 8.2+
- Composer 2.x
- Node.js 18+ & NPM
- SQLite (development) atau PostgreSQL (production)

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/Fachrimardliana16/SIMPEL-sensus-pelanggan-.git
cd SIMPEL-sensus-pelanggan-

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Buat database SQLite
touch database/database.sqlite

# 5. Jalankan migrasi + seeder
php artisan migrate --seed

# 6. Jalankan aplikasi
php artisan serve
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`

---

## 👤 Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | `admin@simpel.test` | `password` |
| **Analyst** | `analyst@simpel.test` | `password` |
| **Surveyor** | `surveyor@simpel.test` | `password` |

> ⚠️ **Penting**: Segera ganti password default setelah deployment ke production.

---

## 🗄 Struktur Database

### Model Utama

| Model | Tabel | Deskripsi |
|-------|-------|-----------|
| `User` | `users` | Pengguna sistem (UUID, soft deletes) |
| `Customer` | `customers` | Data pelanggan PDAM (nolangg, nama, alamat, tarif, status, dll.) |
| `SurveyResponse` | `survey_responses` | Data sensus: koordinat GPS, foto, jawaban kuesioner, skor, status validasi |
| `Question` | `questions` | Master pertanyaan kuesioner (tipe, opsi, urutan, bobot poin) |
| `Survey` | `surveys` | Definisi survey/kuesioner |
| `Tarif` | `tarifs` | Master data tarif PDAM |
| `Unit` | `units` | Master data unit kerja |
| `Status` | `statuses` | Master data status pelanggan |
| `Activity` | `activity_log` | Log audit (Spatie) |
| `Role` | `roles` | Role RBAC (Spatie) |
| `Permission` | `permissions` | Permission RBAC (Spatie) |

### Status Sensus (SurveyResponse)
| Status | Warna | Deskripsi |
|--------|-------|-----------|
| `pending` | 🟠 Orange | Baru dikirim, menunggu review analyst |
| `valid` | 🟢 Hijau | Disetujui oleh analyst |
| `revisi` | 🔴 Merah | Ditolak, perlu perbaikan oleh surveyor |

---

## 🌐 API Endpoints

| Method | URI | Deskripsi |
|--------|-----|-----------|
| `GET` | `/` | Halaman monitoring publik |
| `GET` | `/api/dashboard-stats?filter=daily\|monthly` | Data statistik untuk halaman publik (AJAX) |
| `GET` | `/export/sensus-pdf` | Download PDF laporan sensus (batch) |
| `GET` | `/export/sensus-print/{id}` | Download PDF sensus individual |
| `GET` | `/admin` | Panel Administrator (FilamentPHP) |
| `GET` | `/analyst` | Panel Analyst (FilamentPHP) |
| `GET` | `/surveyor` | Panel Surveyor (FilamentPHP) |

---

## 📂 Struktur Direktori Penting

```
app/
├── Filament/
│   ├── Resources/          # Admin panel resources
│   │   ├── PelangganResource.php
│   │   ├── PertanyaanResource.php
│   │   ├── TarifResource.php
│   │   ├── UnitResource.php
│   │   ├── StatusResource.php
│   │   └── UserResource.php
│   ├── Analyst/Resources/  # Analyst panel
│   │   └── SurveyResponseResource.php
│   ├── Surveyor/Resources/ # Surveyor panel
│   │   └── SensusResource.php
│   └── Widgets/            # 20 dashboard widgets
├── Http/Controllers/
│   └── ExportController.php
├── Models/                 # 11 Eloquent models
└── Providers/Filament/
    ├── AdminPanelProvider.php
    ├── AnalystPanelProvider.php
    └── SurveyorPanelProvider.php

resources/views/
├── welcome.blade.php                   # Halaman monitoring publik
├── reports/census-single-pdf.blade.php # Template PDF single record
├── exports/sensus-pdf.blade.php        # Template PDF batch
└── filament/widgets/
    ├── analyst-map-widget.blade.php    # Peta Leaflet analyst
    ├── surveyor-map-widget.blade.php   # Peta Leaflet surveyor (filterable)
    └── quick-access-widget.blade.php   # Widget akses cepat
```

---

## 📜 Changelog

### v1.0.2-Stable (31 Maret 2026)
- ✅ Peta geospasial interaktif (Leaflet.js) di dashboard Analyst & Surveyor
- ✅ Filter marker peta berdasarkan status (Valid/Pending/Revisi) di panel Surveyor
- ✅ Peta centered di wilayah **Purbalingga, Jawa Tengah**
- ✅ Tombol cetak PDF di halaman view sensus (Analyst & Surveyor)
- ✅ Layout view sensus Split (detail kiri, foto kanan) — konsisten antar panel
- ✅ Quick Access buttons sejajar horizontal
- ✅ Live clock pada halaman monitoring publik
- ✅ Warna tema seragam (primary blue) di seluruh dashboard
- ✅ Tab filter dengan badge count dinamis

### v1.0.0 (30 Maret 2026)
- 🎉 Rilis awal — multi-panel RBAC, input sensus, review & validasi, audit trail

---

## 📄 Lisensi

Proyek ini dikembangkan untuk **PDAM Tirta Perwira Purbalingga**.

---

<p align="center">
  <strong>SIMPEL</strong> — <em>Solusi Cerdas untuk Manajemen Data Pelanggan yang Lebih Akurat.</em>
</p>
