# 🚀 Laravel Project Installation Guide

Panduan ini akan membantu kamu men-setup dan menjalankan proyek Laravel secara lokal.

## 📋 Prasyarat

Sebelum memulai, pastikan kamu sudah menginstall:

-   PHP >= 8.1
-   Composer
-   MySQL / MariaDB
-   Git
-   Laravel CLI (opsional)

> 💡 Gunakan [Laragon](https://laragon.org/) atau [XAMPP](https://www.apachefriends.org/index.html) jika kamu butuh development environment cepat di Windows.

---

## 📦 1. Clone Repository

```
https://github.com/Galsans/Reservasi_Hotel
cd Reservasi_Hotel
```

## ⚙️ 2. Install Dependency PHP

```
composer install

```

## 🔑 3. Salin File .env

```
cp .env.example .env
# atau (Windows)
copy .env.example .env

```

## 🔐 4. Generate App Key & Storage

```
php artisan key:generate
php artisan Storage:link

```

## 🗄️ 5. Jalankan Migrasi dan Seeder

```
php artisan migrate --seed

```

## 🏃 6. Jalankan Server Lokal

```
php artisan serve

```

## 🧩 7. Konfigurasi Google OAuth (Autentikasi)

Untuk mengaktifkan login menggunakan Google, kamu perlu:

1. Buka Google Developer Console

2. Buat project baru → Aktifkan "OAuth 2.0 Client IDs"

3. Masukkan URL Redirect seperti:

```
http://localhost:8000/auth/callback/google

```

4. Setelah client dibuat, kamu akan mendapatkan:

-   GOOGLE_CLIENT_ID

-   GOOGLE_CLIENT_SECRET

5. Tambahkan ke file .env:

```
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/callback/google
```

🛠️ Fitur Aplikasi

👤 Admin Panel

1. CRUD Room (Kamar Hotel)
   Tambah, ubah, dan hapus data kamar yang tersedia untuk reservasi.

2. CRUD Reservation
   Kelola seluruh data reservasi dari semua user.

3. Laporan Pendapatan
   Menampilkan total pendapatan yang didapat dari reservasi.

4. Kamar Populer
   Statistik kamar yang paling banyak dipesan oleh pengguna.

5. Autentikasi via Google
   Login menggunakan akun Google untuk admin dengan keamanan berbasis token.

🙋 User Panel

1. Reservasi Hotel Pribadi
   User dapat melakukan pemesanan kamar hotel.

2. Hapus Reservasi
   Pengguna dapat membatalkan reservasi yang sudah dilakukan.

3. Edit Reservasi
   Pengguna dapat mengedit reservasi yang sudah dilakukan.

4. Generate PDF Konfirmasi
   Setelah melakukan reservasi, user dapat mendownload file PDF sebagai bukti dan mengirimkannya ke pihak admin hotel.

5. Autentikasi via Google
   Login menggunakan akun Google untuk user dengan keamanan berbasis token.
