<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

💼 Darmabakti Application

Sistem Manajemen Keuangan & Administrasi berbasis Laravel

Darmabakti merupakan aplikasi berbasis web yang dirancang untuk membantu pengelolaan administrasi dan keuangan secara lebih terstruktur, mulai dari Uang Masuk, Invoice, Rekening, hingga User Management.

✨ Fitur Utama

Modul

Keterangan

💰 Uang Masuk

Pencatatan dan pengelolaan transaksi uang masuk

🧾 Invoice

Membuat, mengelola, dan mencetak invoice

🏦 Rekening

Pengelolaan data rekening

📊 Dashboard

Ringkasan dan monitoring data keuangan

👥 User Management

Pengelolaan pengguna aplikasi

📄 Invoice PDF

Preview dan pembuatan invoice dalam format PDF

🔐 Authentication

Sistem login dan pengelolaan akses pengguna

🛠️ Teknologi

<p>
<img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
<img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white">
<img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white">
<img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white">
<img src="https://img.shields.io/badge/Vite-8-646CFF?style=for-the-badge&logo=vite&logoColor=white">
</p>

🚀 Instalasi

1. Clone Repository

git clone <url-repository>
cd darmabakti

2. Install Dependencies

Install dependency Laravel:

composer install

Install dependency frontend:

npm install

Build asset:

npm run build

3. Konfigurasi Environment

⚠️ Penting: Jangan pernah melakukan commit atau push file .env ke repository GitHub karena dapat berisi password database, API key, dan konfigurasi sensitif lainnya.

Salin file environment:

cp .env.example .env

Kemudian sesuaikan konfigurasi database pada .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

4. Generate Application Key

php artisan key:generate

5. Setup Database

Pastikan database sudah dibuat, kemudian jalankan migration:

php artisan migrate

6. Jalankan Aplikasi

php artisan serve

Aplikasi dapat diakses melalui:

http://127.0.0.1:8000

🖥️ Tampilan Aplikasi

📊 Dashboard

💰 Uang Masuk

➕ Tambah Uang Masuk

📈 Report Uang Masuk

🧾 Invoice

🖨️ Cetak Invoice

📄 Invoice PDF

🏦 Daftar Rekening

👥 User Management

✏️ Edit User

📁 Struktur Project

darmabakti/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md

⚙️ Perintah Laravel

Clear seluruh cache:

php artisan optimize:clear

Melihat daftar route:

php artisan route:list

Melihat status migration:

php artisan migrate:status

Masuk ke Laravel Tinker:

php artisan tinker

Menjalankan test:

php artisan test

🔒 Security

Sebelum aplikasi digunakan pada production, pastikan:

.env tidak masuk repository

APP_DEBUG=false

Password database menggunakan credential yang aman

Gunakan HTTPS

Gunakan password administrator yang kuat

Batasi akses database

Jangan menyimpan API key atau credential sensitif di source code

Untuk production:

APP_ENV=production
APP_DEBUG=false

📌 Production Deployment

Setelah aplikasi dipasang pada server production:

composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm install
npm run build
php artisan storage:link

Web server harus diarahkan ke:

/var/www/html/darmabakti/public

Jangan mengarahkan document root ke folder utama project Laravel. Gunakan folder public.

👨‍💻 Development

Untuk development dengan Vite:

npm run dev

Kemudian jalankan Laravel:

php artisan serve

📄 License

Project ini menggunakan lisensi MIT.

<p align="center">
  Made with ❤️ using Laravel
</p>
