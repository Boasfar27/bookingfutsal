# 🏟️ FutsalPro - Platform Booking Lapangan Futsal Online

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![Filament](https://img.shields.io/badge/Filament-3.0-yellow.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)

## 📝 Deskripsi Proyek

FutsalPro adalah platform berbasis web untuk melakukan pemesanan lapangan futsal secara online. Pengguna dapat melihat ketersediaan waktu, melakukan booking, dan mengunggah bukti pembayaran. Sistem ini dibangun menggunakan Laravel 12 dan Filament Admin Panel, dengan 3 role utama: User, Admin, dan Superadmin.

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 12
- **Admin Panel:** Filament v3
- **Styling:** TailwindCSS
- **Frontend:** Blade/Livewire
- **Permission:** Spatie Permission
- **Export:** Laravel Excel
- **Database:** MySQL
- **API:** WhatsApp API (Opsional)

## 👥 Role & Hak Akses

### 1. User
- ✅ Register / Login
- ✅ Melihat daftar lapangan & deskripsi  
- ✅ Melihat jadwal kosong berdasarkan tanggal & jam
- ✅ Booking dengan memilih tanggal + jam + durasi
- ✅ Mengunggah bukti pembayaran
- ✅ Melihat status booking (Pending, Disetujui, Ditolak)
- ✅ Riwayat pemesanan

### 2. Admin (Pemilik Lapangan)
- ✅ Login ke dashboard Filament
- ✅ Mengelola data lapangan (foto, deskripsi, harga per jam, jam operasional)
- ✅ Melihat daftar booking masuk
- ✅ Melihat & mengelola slot waktu
- ✅ Konfirmasi / tolak bukti pembayaran
- ✅ Export laporan transaksi

### 3. Superadmin
- ✅ Akses semua data sistem
- ✅ Mengelola data admin & user
- ✅ Mengelola data global (harga default, jam buka default, dll)
- ✅ Melihat laporan menyeluruh seluruh lapangan

## 🧩 Fitur Utama

### 📆 1. Sistem Booking Slot Waktu
- User memilih tanggal dan jam yang tersedia
- Sistem akan otomatis memblokir slot yang sudah dibooking (status disetujui)
- Booking minimal 1 jam, maksimal X jam per hari
- Admin bisa menandai jam tertentu sebagai tidak tersedia

### 📄 2. Pembayaran Manual via Upload Bukti Transfer
- Setelah booking, user diminta transfer ke rekening tertentu
- User upload bukti transfer (image/pdf)
- Admin menerima notifikasi & memverifikasi manual
- Status booking berubah menjadi:
  - ⏳ **Menunggu Konfirmasi**
  - ✅ **Disetujui**
  - ❌ **Ditolak**

### 📊 3. Dashboard Filament untuk Admin & Superadmin
- Tabel booking + status
- Filter booking per tanggal / status / user
- Riwayat dan total pemasukan
- Export Excel per lapangan

### 🏟️ 4. Manajemen Lapangan
- Nama lapangan, jenis lapangan (Indoor/Outdoor)
- Foto & deskripsi
- Jam buka per hari
- Harga per jam

## 📚 Struktur Database Utama

| Tabel | Keterangan |
|-------|------------|
| `users` | Data user umum |
| `roles` | Role: user, admin, superadmin |
| `fields` | Data lapangan (nama, foto, lokasi, harga) |
| `bookings` | Transaksi booking (user_id, field_id, tgl, jam_mulai, jam_selesai, status) |
| `payments` | Bukti pembayaran (file, status, keterangan) |
| `schedules` | Data jadwal tidak tersedia (manual blocking slot) |

## ✅ Task Progress

| No | Task | Status | Tanggal Update |
|----|------|--------|----------------|
| 1 | Setup Laravel + Filament | ✅ Done | 2025-01-23 |
| 2 | Install Dependencies (Spatie Permission, Laravel Excel) | ✅ Done | 2025-01-23 |
| 3 | Setup Role & Permission | ✅ Done | 2025-01-23 |
| 4 | Buat Struktur Database & Migrasi | ✅ Done | 2025-01-23 |
| 5 | Buat Eloquent Models & Relasi | ✅ Done | 2025-01-23 |
| 6 | Dashboard Admin Filament | ✅ Done | 2025-01-23 |
| 7 | Halaman Booking User | ❌ Belum Dikerjakan | - |
| 8 | Upload Bukti Pembayaran | ❌ Belum Dikerjakan | - |
| 9 | Sistem Booking Slot Real-time | ❌ Belum Dikerjakan | - |
| 10 | Export Laporan Excel | ❌ Belum Dikerjakan | - |
| 11 | Testing & Deployment Setup | ❌ Belum Dikerjakan | - |

## 🚀 Installation

```bash
# Clone repository
git clone <repository-url>
cd bookingfutsal

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Admin panel
php artisan make:filament-user

# Start development server
php artisan serve
```

## 📋 Development Notes

- **Database:** SQLite untuk development, MySQL untuk production
- **Frontend:** Mobile-friendly dengan responsive design
- **Notifications:** Email & WhatsApp API integration (optional)
- **Security:** Rate limiting, validation, dan CSRF protection
- **Performance:** Caching, query optimization

## 💬 Pitch Singkat

*"FutsalPro adalah solusi digital untuk pengelolaan booking lapangan futsal secara modern. Dengan fitur cek jadwal real-time dan pembayaran manual via bukti transfer, Anda bisa memudahkan pelanggan dan memaksimalkan jam operasional lapangan. Dibangun dengan Laravel + Filament, tampilannya profesional dan responsif."*

---

**Developed with ❤️ using Laravel 12 + Filament v3**
