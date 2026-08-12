<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Sistem Manajemen Keuangan - DARMABAKTI

Aplikasi berbasis Laravel untuk membantu pengelolaan keuangan dan administrasi perusahaan. Aplikasi mencakup pencatatan Uang Masuk, pembuatan Invoice, pengelolaan Rekening, Master Barang, User Management, serta laporan keuangan.

Tampilan Aplikasi

Halaman

Dokumentasi

Dashboard Report

Uang Masuk

Tambah Uang Masuk

Report Uang Masuk

Invoice

Cetak Invoice

Invoice PDF

Daftar Rekening

User Management

Edit User

Darmabakti

Aplikasi manajemen keuangan berbasis Laravel yang digunakan untuk mencatat dan mengelola transaksi keuangan, invoice, rekening, barang, serta pengguna aplikasi.

Aplikasi ini dirancang untuk membantu proses administrasi menjadi lebih terstruktur dan memudahkan monitoring data keuangan melalui dashboard dan laporan.

Fitur

Uang Masuk

Mencatat transaksi uang masuk

Menampilkan data transaksi

Edit dan hapus transaksi

Import data menggunakan Excel

Filter dan laporan transaksi

Laporan Keuangan

Dashboard laporan

Rekap data uang masuk

Monitoring transaksi berdasarkan data keuangan

Invoice

Membuat invoice baru

Mengelola data invoice

Edit invoice

Hapus invoice

Menandai invoice sebagai lunas

Mencetak invoice

Membuat invoice dalam format PDF

Master Data

Master Barang

Master Rekening

Data pelanggan pada transaksi invoice

User Management

Menambahkan user

Mengubah user

Menghapus user

Pengaturan role user

Authentication dan logout

Dokumen

Menampilkan data dokumen dari API

Preview dokumen

Download dokumen

Import Data

Import transaksi Uang Masuk dari Excel

Mendukung import berdasarkan jenis data Pemerintah dan Swasta

Teknologi

Laravel 13

PHP 8.3+

MySQL / MariaDB

Blade

Tailwind CSS

Vite

Laravel Excel (Maatwebsite)

Instalasi

1. Clone Repository

git clone https://github.com/raihanryd1801/darmabakti.git
cd darmabakti

2. Install Dependency

Install dependency Laravel:

composer install

Install dependency frontend:

npm install

Build asset:

npm run build

3. Copy File Environment

Linux:

cp .env.example .env

Windows:

copy .env.example .env

4. Generate Application Key

php artisan key:generate

5. Konfigurasi Database

Sesuaikan file .env dengan database yang digunakan.

Contoh:

APP_NAME=Darmabakti
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=darmabakti
DB_USERNAME=root
DB_PASSWORD=

Jangan melakukan commit atau push file .env ke GitHub karena file tersebut dapat berisi credential database dan konfigurasi sensitif.

6. Jalankan Migration

php artisan migrate

Jika database ingin dibuat ulang dari awal:

php artisan migrate:fresh

Gunakan migrate:fresh hanya pada environment development karena seluruh tabel akan dihapus.

7. Jalankan Aplikasi

Untuk development:

php artisan serve

Aplikasi dapat diakses melalui:

http://127.0.0.1:8000

Jika ingin menjalankan Vite saat development:

npm run dev

Membuat User Admin

Gunakan Laravel Tinker:

php artisan tinker

Kemudian:

App\Models\User::create([
'name' => 'Administrator',
'email' => 'admin@example.com',
'password' => bcrypt('password'),
'role' => 'admin'
]);

Keluar dari Tinker:

exit

Gunakan password yang aman untuk environment production.

Struktur Folder

darmabakti/
├── app/
│ ├── Http/
│ │ ├── Controllers/
│ │ │ ├── AuthController.php
│ │ │ ├── BarangController.php
│ │ │ ├── DokumenApiController.php
│ │ │ ├── InvoiceController.php
│ │ │ ├── RekeningController.php
│ │ │ ├── UangMasukController.php
│ │ │ └── UserController.php
│ │ ├── Middleware/
│ │ └── ...
│ ├── Imports/
│ └── Models/
│
├── database/
│ ├── factories/
│ ├── migrations/
│ └── seeders/
│
├── public/
│
├── resources/
│ └── views/
│
├── routes/
│ ├── web.php
│ └── console.php
│
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md

Cara Penggunaan

Login menggunakan akun yang telah dibuat.

Gunakan menu Uang Masuk untuk mencatat transaksi.

Gunakan fitur import apabila ingin memasukkan data dari Excel.

Gunakan Dashboard Report untuk melihat ringkasan keuangan.

Gunakan menu Invoice untuk membuat dan mengelola invoice.

Tandai invoice sebagai lunas setelah pembayaran diterima.

Gunakan menu Rekening untuk mengelola data rekening.

Gunakan menu Barang untuk mengelola master barang.

Gunakan User Management untuk mengelola pengguna aplikasi.

Gunakan menu Dokumen API untuk preview atau download dokumen yang tersedia.

Dependensi

Dependency utama yang digunakan:

Laravel Framework 13
PHP 8.3+
Laravel Tinker
Maatwebsite Laravel Excel
MySQL / MariaDB
Node.js
NPM
Vite
Tailwind CSS

Untuk melihat dependency lengkap:

composer show

dan:

npm list

Deployment Production

Untuk deployment pada server Linux:

composer install --no-dev --optimize-autoloader

Kemudian:

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

Build frontend:

npm install
npm run build

Buat symbolic link storage:

php artisan storage:link

Document root web server harus diarahkan ke:

/var/www/html/darmabakti/public

Jangan mengarahkan document root ke folder utama project Laravel.

Pastikan permission folder berikut sesuai:

storage/
bootstrap/cache/

Konfigurasi Production

Pada production, ubah konfigurasi .env:

APP_ENV=production
APP_DEBUG=false

Gunakan:

Password database yang kuat

HTTPS

Credential database yang aman

Access control pada database

Backup database secara berkala

Perintah Laravel yang Berguna

Clear cache:

php artisan optimize:clear

Melihat route:

php artisan route:list

Melihat status migration:

php artisan migrate:status

Masuk Tinker:

php artisan tinker

Menjalankan test:

php artisan test

Troubleshooting

Jika aplikasi mengalami masalah cache atau konfigurasi:

php artisan optimize:clear

Jika migration bermasalah, periksa:

php artisan migrate:status

Jika terjadi masalah permission pada Linux:

storage/
bootstrap/cache/

Pastikan kedua folder tersebut dapat ditulis oleh user web server.

Log Laravel dapat diperiksa pada:

storage/logs/laravel.log

Catatan

Pastikan environment yang digunakan memenuhi requirement project:

PHP : 8.3+
Laravel : 13
Database : MySQL / MariaDB
Node.js : 20.19+ atau 22 LTS
Composer : 2.x

Jangan memasukkan file .env, password database, API key, atau credential lainnya ke repository GitHub.

Lisensi

Hak cipta © Darmabakti.

Aplikasi ini ditujukan untuk penggunaan internal perusahaan.

Bantuan

Apabila mengalami kendala, periksa log aplikasi pada:

storage/logs/laravel.log

atau hubungi administrator sistem.
