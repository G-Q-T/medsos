# ConnectSpace — Aplikasi Media Sosial Modern

ConnectSpace adalah platform media sosial modern yang dibangun menggunakan framework **Laravel 12** dan antarmuka **Bootstrap 5**. Aplikasi ini dilengkapi fitur interaksi postingan, sistem komentar bertingkat (nested replies), sistem like, profil pengguna, hingga deteksi lokasi otomatis berbasis **Browser Geolocation API**.

---

## 🌟 Fitur Utama

- **Autentikasi Pengguna**: Registrasi akun baru, login dengan session aman, dan logout.
- **Feed Postingan**:
  - Teks postingan dengan batas maksimal **500 karakter** dan penghitung karakter *real-time*.
  - Upload gambar pada postingan (format JPG, JPEG, PNG).
  - Indikator disable otomatis pada tombol posting jika teks melebihi limit.
  - Hapus dan edit postingan (khusus pemilik postingan).
- **Sistem Like**: Suka dan batal suka (*toggle like*) pada postingan dan komentar.
- **Komentar & Balasan Bertingkat (Nested Replies)**:
  - Berikan komentar utama pada postingan.
  - Balas komentar pengguna lain (*threaded replies*).
  - Dukungan upload foto pada komentar.
  - Suka komentar (*comment likes*).
  - Hak hapus komentar khusus bagi pemilik komentar itu sendiri.
- **Lokasi Otomatis via Geolocation API**:
  - Menggunakan browser `navigator.geolocation` untuk mendeteksi koordinat *Latitude* dan *Longitude* secara otomatis.
  - Terintegrasi dengan *reverse geocoding* untuk menyematkan nama provinsi dan negara (misal: *Jakarta, Indonesia*).
  - Koordinat terhubung langsung dengan **Google Maps**.
- **Profil Pengguna**:
  - Melihat profil diri sendiri dan profil pengguna lain.
  - Edit bio dan ganti foto profil (*avatar*).
  - Menampilkan riwayat postingan pengguna di halaman profil.
- **Pengaturan Keamanan**: Fitur ganti password dengan verifikasi password lama.

---

## 🛠️ Persyaratan Sistem (Prerequisites)

Pastikan lingkungan komputer Anda telah terinstal:
- **PHP** >= 8.2 (Disarankan PHP 8.4)
- **Composer** (Package Manager PHP)
- **MySQL** / **MariaDB** (Direkomendasikan menggunakan [Laragon](https://laragon.org/) atau XAMPP)
- **Git**

---

## 🚀 Panduan Instalasi (Cloning dari GitHub)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal Anda:

### 1. Clone Repository dari GitHub
Buka terminal / PowerShell / Git Bash, lalu jalankan perintah:
```bash
git clone https://github.com/username-anda/medsos.git
```
*(Ganti URL di atas dengan link repository GitHub Anda)*

### 2. Masuk ke Direktori Proyek
```bash
cd medsos
```

### 3. Install Dependensi PHP via Composer
Jalankan perintah ini untuk menginstal seluruh package Laravel yang dibutuhkan:
```bash
composer install
```

### 4. Salin File Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
- **Di Windows (CMD / PowerShell):**
  ```bash
  copy .env.example .env
  ```
- **Di Linux / macOS / Git Bash:**
  ```bash
  cp .env.example .env
  ```

### 5. Generate Application Key
Buat kunci enkripsi aplikasi Laravel:
```bash
php artisan key:generate
```

### 6. Konfigurasi Database
1. Pastikan server **MySQL** Anda sudah berjalan (misal: klik *Start All* di Laragon atau jalankan modul MySQL di XAMPP).
2. Buat database baru bernama `medsos` melalui phpMyAdmin, HeidiSQL, atau terminal:
   ```sql
   CREATE DATABASE medsos;
   ```
3. Buka file `.env` di text editor Anda, lalu sesuaikan bagian konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=medsos
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan pengaturan MySQL Anda)*

### 7. Jalankan Migrasi Database
Jalankan migrasi untuk membuat seluruh tabel di database:
```bash
php artisan migrate
```
*(Opsional: Anda juga dapat menjalankan `php artisan migrate --seed` jika ingin menyertakan data uji coba)*

### 8. Hubungkan Direktori Storage (Penyimpanan Media)
Untuk memastikan file gambar avatar dan postingan dapat diakses oleh browser, buat link symlink storage:
```bash
php artisan storage:link
```

### 9. Jalankan Aplikasi

Pilih salah satu cara berikut untuk menjalankan aplikasi:

- **Cara A: Menggunakan Laragon (Direkomendasikan)**
  Jika proyek diletakkan di direktori `C:\laragon\www\medsos`, cukup buka browser Anda dan akses:
  ```
  http://medsos.test
  ```

- **Cara B: Menggunakan PHP Built-in Server**
  Jalankan perintah Artisan di terminal:
  ```bash
  php artisan serve
  ```
  Lalu buka browser Anda dan kunjungi:
  ```
  http://localhost:8000
  ```
  *(Jika port 8000 bentrok dengan web server lain, gunakan `php artisan serve --port=8001`)*

---

## 🧪 Menjalankan Automated Tests

Aplikasi ini telah dilengkapi dengan rangkaian pengujian fitur (*Feature Tests*) dan unit (*Unit Tests*) menggunakan PHPUnit:

```bash
php artisan test
```
atau
```bash
vendor/bin/phpunit
```

---

## 📂 Struktur Direktori Utama

```
medsos/
├── app/
│   ├── Http/Controllers/     # Controller (Post, Comment, Like, Profile, Auth)
│   └── Models/               # Model Eloquent (User, Post, Comment, Like, CommentLike)
├── database/
│   └── migrations/           # Skema tabel database
├── public/
│   └── css/style.css         # Custom stylesheet modern ConnectSpace
├── resources/
│   └── views/                # Template Blade (layouts, components, feed, auth, profile)
├── routes/
│   └── web.php               # Rute aplikasi web
└── tests/                    # Feature & Unit Tests
```

---

## 💻 Tech Stack

- **Backend**: Laravel 12, PHP 8.4
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating, Bootstrap 5.3, Bootstrap Icons
- **Geolocation**: Browser Geolocation API & OpenStreetMap Nominatim
- **Code Formatter**: Laravel Pint
- **Testing**: PHPUnit

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pelatihan / pengembangan aplikasi web dan bersifat *open-source* di bawah lisensi [MIT License](LICENSE).
