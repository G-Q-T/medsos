# PRODUCT REQUIREMENTS DOCUMENT (PRD)

# CONNECTSPACE – SOCIAL MEDIA APPLICATION

## 1. Informasi Proyek

**Nama Aplikasi:** ConnectSpace
**Jenis Aplikasi:** Social Media Web Application
**Platform:** Web Application
**Backend:** Laravel
**Frontend:** Blade Template + Bootstrap 5
**Database:** MySQL
**Arsitektur:** Model-View-Controller (MVC)

### Deskripsi Produk

ConnectSpace merupakan aplikasi media sosial sederhana yang memungkinkan pengguna untuk melakukan registrasi, login, mengelola profil, membagikan status berupa teks dan gambar, memberikan like, serta menambahkan komentar pada postingan pengguna lain.

Aplikasi dikembangkan sebagai Minimum Viable Product (MVP) untuk memenuhi kebutuhan Uji Kompetensi Junior Web Programmer.

Prioritas pengembangan adalah fungsionalitas, keamanan, integritas database, dan kelengkapan alur bisnis.

---

## 2. Tujuan Pengembangan

1. Membuat aplikasi media sosial dengan sistem autentikasi pengguna.
2. Memungkinkan pengguna mengelola informasi profil dan foto profil.
3. Memungkinkan pengguna membuat dan menghapus postingan.
4. Menyediakan fitur like dengan mekanisme toggle.
5. Menyediakan fitur komentar dengan dukungan gambar.
6. Menerapkan relasi database dan integritas data yang benar.
7. Memastikan setiap pengguna hanya dapat mengakses dan mengelola data sesuai hak aksesnya.

---

## 3. Tech Stack

### Backend

- Laravel 12 atau versi Laravel stabil yang kompatibel dengan environment.
- PHP 8.2 atau lebih baru.
- Laravel Eloquent ORM.
- Laravel Middleware untuk autentikasi dan proteksi route.
- Laravel Validation untuk validasi input.
- Laravel Storage untuk pengelolaan file gambar.

### Frontend

- Laravel Blade sebagai template engine.
- Bootstrap 5 sebagai framework CSS.
- Bootstrap Icons untuk ikon.
- JavaScript untuk interaksi sederhana.
- Bootstrap Modal jika diperlukan.

### Database

- MySQL.
- Migration untuk pembuatan struktur database.
- Eloquent Relationship untuk relasi antartabel.

### Ketentuan Teknis

- Tidak menggunakan React, Vue, Inertia, atau framework frontend tambahan.
- Tidak menggunakan Tailwind CSS.
- Tidak menggunakan API terpisah atau microservices.
- Seluruh proses aplikasi dikelola melalui Laravel MVC.
- Gunakan Laravel Blade dan Bootstrap untuk seluruh halaman.

---

## 4. Target Pengguna dan Hak Akses

Aplikasi memiliki satu jenis pengguna utama, yaitu User.

Setiap pengguna yang berhasil melakukan registrasi dapat langsung menggunakan seluruh fitur aplikasi.

### Hak akses User

- Registrasi akun.
- Login dan logout.
- Melihat feed postingan.
- Membuat postingan.
- Menghapus postingan milik sendiri.
- Memberikan like dan unlike.
- Memberikan komentar.
- Mengunggah gambar pada komentar.
- Mengubah profil.
- Mengubah foto profil.
- Mengubah password.

Tidak diperlukan sistem admin pada versi MVP ini.

---

## 5. Functional Requirements

### 5.1 Authentication

#### A. Registrasi

Pengguna dapat membuat akun melalui halaman registrasi.

Form registrasi:

- Nama lengkap.
- Email.
- Password.
- Konfirmasi password.

Validasi:

- Semua field wajib diisi.
- Email harus memiliki format yang valid.
- Email harus unik.
- Password minimal 6 karakter.
- Konfirmasi password harus sama dengan password.

Business Logic:

1. Pengguna mengisi formulir registrasi.
2. Sistem melakukan validasi.
3. Password di-hash sebelum disimpan ke database.
4. Data pengguna disimpan.
5. Sistem langsung membuat sesi login pengguna.
6. Pengguna otomatis diarahkan ke halaman Feed.

**Penting:** Setelah registrasi berhasil, pengguna tidak boleh diarahkan kembali ke halaman login.

#### B. Login

Form login:

- Email.
- Password.

Ketentuan:

- Email dan password diverifikasi menggunakan sistem autentikasi Laravel.
- Password diverifikasi terhadap password hash di database.
- Jika berhasil, pengguna diarahkan ke halaman Feed.
- Jika gagal, tampilkan pesan kesalahan.

#### C. Logout

Ketentuan:

- Menghapus sesi autentikasi pengguna.
- Melakukan session invalidation.
- Melakukan regenerasi CSRF token.
- Mengarahkan pengguna ke halaman login.

#### D. Proteksi Route

Gunakan middleware `auth` dan `guest`.

Ketentuan:

- Pengguna yang belum login tidak dapat membuka halaman Feed.
- Pengguna yang belum login tidak dapat membuat postingan.
- Pengguna yang belum login tidak dapat mengakses halaman profil.
- Pengguna yang sudah login tidak dapat membuka halaman login dan registrasi.
- Pengguna yang belum login diarahkan ke login dengan notifikasi.

---

### 5.2 User Profile

Pengguna memiliki halaman profil untuk melihat dan memperbarui informasi akun.

Informasi profil:

- Nama pengguna.
- Email (tidak dapat diubah pada MVP).
- Bio atau deskripsi singkat.
- Foto profil/avatar.

Fitur:

- Melihat profil sendiri.
- Mengubah nama pengguna.
- Mengubah bio.
- Mengunggah foto profil.
- Mengganti foto profil.

Ketentuan foto:

- Mendukung format JPG, JPEG, PNG.
- Validasi ukuran dan tipe file.
- Simpan gambar menggunakan Laravel Storage.
- Simpan path gambar ke database.

Avatar wajib ditampilkan pada:

- Navbar.
- Setiap postingan pengguna.
- Setiap komentar pengguna.

### 5.3 Change Password

Pengguna dapat mengganti password melalui halaman pengaturan akun.

Form:

- Password lama.
- Password baru.
- Konfirmasi password baru.

Business Logic:

1. Pengguna memasukkan password lama.
2. Sistem memverifikasi password lama menggunakan Hash::check().
3. Jika tidak sesuai, tampilkan pesan kesalahan.
4. Jika sesuai, lakukan validasi password baru.
5. Hash password baru dan simpan ke database.

Password baru minimal 6 karakter dan harus cocok dengan konfirmasi.

---

### 5.4 Home Feed

Feed merupakan halaman utama aplikasi setelah pengguna berhasil login.

Fungsi utama:

- Menampilkan seluruh postingan pengguna.
- Menampilkan postingan terbaru pada posisi paling atas.
- Menampilkan identitas pemilik postingan.
- Menampilkan foto profil pemilik postingan.
- Menampilkan waktu pembuatan postingan.
- Menampilkan isi teks dan gambar postingan.
- Menampilkan jumlah like.
- Menampilkan daftar komentar.

Urutan postingan:

- Menggunakan created_at secara descending.
- Postingan terbaru ditampilkan terlebih dahulu.

Gunakan pagination untuk membatasi jumlah postingan per halaman.

### 5.5 Create Post

Pengguna dapat membuat postingan baru melalui halaman Feed.

Form postingan:

- Isi status.
- Upload gambar (opsional).

Ketentuan:

- Pengguna dapat membuat postingan berupa teks saja.
- Pengguna dapat membuat postingan berupa gambar saja.
- Pengguna dapat membuat postingan berupa teks dan gambar.
- Postingan tidak boleh kosong.
- Minimal salah satu dari teks atau gambar harus tersedia.

Validasi gambar:

- Format JPG, JPEG, PNG.
- Maksimal ukuran file 2 MB.
- Simpan gambar ke storage Laravel.
- Simpan path gambar ke database.

Setelah berhasil:

- Postingan disimpan ke database.
- Pengguna mendapatkan notifikasi berhasil.
- Postingan langsung muncul pada posisi teratas feed.

### 5.6 Delete Post

Pengguna dapat menghapus postingan miliknya sendiri.

Ketentuan:

- Tombol hapus hanya muncul pada postingan milik pengguna yang sedang login.
- Pengguna tidak dapat menghapus postingan pengguna lain.
- Otorisasi wajib dilakukan di backend, bukan hanya menyembunyikan tombol.
- Gunakan Laravel Policy atau pemeriksaan ownership.
- Ketika postingan dihapus, data likes dan comments ikut terhapus melalui foreign key cascade.
- File gambar postingan juga harus dihapus dari storage jika ada.

### 5.7 Like dan Unlike

Pengguna dapat memberikan like pada postingan.

Business Logic:

- Satu pengguna hanya boleh memberikan satu like pada satu postingan.
- Klik tombol Like pertama kali akan membuat data like.
- Klik kembali tombol Like akan menghapus data like (Unlike).
- Jumlah like harus diperbarui secara akurat.
- Status tombol harus menunjukkan apakah pengguna sudah memberikan like.

Database:

- Gunakan tabel likes.
- Terapkan unique constraint pada kombinasi user_id dan post_id.
- Pastikan validasi backend tetap mencegah duplikasi like.

Implementasi:
Gunakan relasi Eloquent many-to-many antara User dan Post melalui tabel likes.

### 5.8 Comments

Pengguna dapat memberikan komentar pada postingan.

Form komentar:

- Isi komentar.
- Upload gambar (opsional).

Ketentuan:

- Komentar dapat berupa teks saja.
- Komentar dapat berupa teks dan gambar.
- Komentar wajib memiliki teks atau gambar.
- Gambar bersifat opsional.
- Validasi gambar JPG, JPEG, PNG dengan maksimal 2 MB.

Informasi yang ditampilkan:

- Foto profil pengomentar.
- Nama pengomentar.
- Isi komentar.
- Gambar komentar jika tersedia.
- Waktu komentar dibuat.

Komentar ditampilkan pada postingan yang sesuai.

---

## 6. Database Design

Aplikasi menggunakan empat tabel utama.

### 6.1 Table: users

| Field      | Type              | Keterangan         |
| ---------- | ----------------- | ------------------ |
| id         | BIGINT            | Primary Key        |
| name       | VARCHAR(100)      | Nama lengkap       |
| email      | VARCHAR(150)      | Unique             |
| password   | VARCHAR(255)      | Password hash      |
| avatar     | VARCHAR(255) NULL | Path foto profil   |
| bio        | TEXT NULL         | Deskripsi pengguna |
| created_at | TIMESTAMP         | Waktu dibuat       |
| updated_at | TIMESTAMP         | Waktu diperbarui   |

### 6.2 Table: posts

| Field      | Type              | Keterangan       |
| ---------- | ----------------- | ---------------- |
| id         | BIGINT            | Primary Key      |
| user_id    | BIGINT            | FK users.id      |
| content    | TEXT NULL         | Isi postingan    |
| image      | VARCHAR(255) NULL | Path gambar      |
| created_at | TIMESTAMP         | Waktu dibuat     |
| updated_at | TIMESTAMP         | Waktu diperbarui |

Foreign Key:

- user_id mengarah ke users.id.
- ON DELETE CASCADE.

### 6.3 Table: likes

| Field      | Type      | Keterangan   |
| ---------- | --------- | ------------ |
| id         | BIGINT    | Primary Key  |
| user_id    | BIGINT    | FK users.id  |
| post_id    | BIGINT    | FK posts.id  |
| created_at | TIMESTAMP | Waktu dibuat |

Foreign Key:

- user_id mengarah ke users.id.
- post_id mengarah ke posts.id.
- Keduanya menggunakan ON DELETE CASCADE.

Constraint:

- UNIQUE(user_id, post_id).

### 6.4 Table: comments

| Field      | Type              | Keterangan       |
| ---------- | ----------------- | ---------------- |
| id         | BIGINT            | Primary Key      |
| post_id    | BIGINT            | FK posts.id      |
| user_id    | BIGINT            | FK users.id      |
| comment    | TEXT NULL         | Isi komentar     |
| image      | VARCHAR(255) NULL | Path gambar      |
| created_at | TIMESTAMP         | Waktu dibuat     |
| updated_at | TIMESTAMP         | Waktu diperbarui |

Foreign Key:

- post_id mengarah ke posts.id.
- user_id mengarah ke users.id.
- Keduanya menggunakan ON DELETE CASCADE.

### 6.5 Database Relationships

- User memiliki banyak Post.
- Post dimiliki oleh satu User.
- User memiliki banyak Comment.
- Comment dimiliki oleh satu User.
- Post memiliki banyak Comment.
- Comment dimiliki oleh satu Post.
- User memiliki banyak Like.
- Post memiliki banyak Like.
- User dan Post memiliki relasi Many-to-Many melalui tabel Likes.

---

## 7. Laravel Models dan Relationships

Buat empat model utama:

1. User
2. Post
3. Like
4. Comment

Relasi yang harus tersedia:

### User Model

- posts(): hasMany(Post::class)
- comments(): hasMany(Comment::class)
- likes(): hasMany(Like::class)
- likedPosts(): belongsToMany(Post::class, 'likes')

### Post Model

- user(): belongsTo(User::class)
- comments(): hasMany(Comment::class)
- likes(): hasMany(Like::class)
- likedByUsers(): belongsToMany(User::class, 'likes')

### Comment Model

- user(): belongsTo(User::class)
- post(): belongsTo(Post::class)

### Like Model

- user(): belongsTo(User::class)
- post(): belongsTo(Post::class)

Pastikan nama foreign key sesuai dengan struktur database.

---

## 8. UI/UX Requirements

### Design Direction

Gunakan tampilan sederhana, bersih, dan mudah dipahami.

Referensi pendekatan desain:

- Bootstrap 5 default components.
- Layout sederhana seperti media sosial pada umumnya.
- Dominasi warna putih dan abu-abu muda.
- Warna biru sebagai warna utama untuk tombol dan elemen interaktif.
- Card untuk postingan.
- Border radius sederhana.
- Typography yang mudah dibaca.

Jangan membuat desain terlalu kompleks.

### 8.1 Halaman Login

Komponen:

- Logo/nama ConnectSpace.
- Form email.
- Form password.
- Tombol Login.
- Link menuju Registrasi.

### 8.2 Halaman Register

Komponen:

- Nama lengkap.
- Email.
- Password.
- Konfirmasi password.
- Tombol Register.
- Link menuju Login.

### 8.3 Halaman Feed

Layout:

- Navbar bagian atas.
- Logo ConnectSpace.
- Foto profil pengguna.
- Dropdown pengaturan akun.
- Tombol Logout.
- Area buat postingan.
- Daftar postingan.

Setiap card postingan memiliki:

- Avatar pengguna.
- Nama pengguna.
- Waktu posting.
- Isi status.
- Gambar postingan.
- Tombol Like / Unlike.
- Jumlah like.
- Form komentar.
- Daftar komentar.
- Tombol hapus untuk pemilik postingan.

### 8.4 Halaman Profile

Komponen:

- Foto profil.
- Nama pengguna.
- Bio.
- Tombol Edit Profile.
- Daftar postingan milik pengguna.

### 8.5 Halaman Edit Profile

Komponen:

- Input nama.
- Input bio.
- Upload avatar.
- Tombol Simpan.
- Tombol Kembali.

### 8.6 Halaman Change Password

Komponen:

- Password lama.
- Password baru.
- Konfirmasi password baru.
- Tombol Simpan.

### 8.7 Responsive Design

Gunakan Bootstrap Grid dan utility classes.

Tampilan harus tetap nyaman digunakan pada:

- Desktop.
- Tablet.
- Smartphone.

---

## 9. Route Planning

Gunakan route web Laravel.

### Guest Routes

| Method | URL       | Function          |
| ------ | --------- | ----------------- |
| GET    | /login    | Form login        |
| POST   | /login    | Proses login      |
| GET    | /register | Form registrasi   |
| POST   | /register | Proses registrasi |

### Authenticated Routes

| Method | URL                    | Function             |
| ------ | ---------------------- | -------------------- |
| POST   | /logout                | Logout               |
| GET    | /                      | Home Feed            |
| POST   | /posts                 | Membuat postingan    |
| DELETE | /posts/{post}          | Menghapus postingan  |
| POST   | /posts/{post}/like     | Toggle like          |
| POST   | /posts/{post}/comments | Membuat komentar     |
| GET    | /profile               | Melihat profil       |
| GET    | /profile/edit          | Form edit profil     |
| PUT    | /profile               | Update profil        |
| GET    | /settings/password     | Form ubah password   |
| PUT    | /settings/password     | Proses ubah password |

Gunakan middleware auth untuk seluruh route yang membutuhkan sesi login.

---

## 10. Security Requirements

1. Password wajib disimpan menggunakan hashing Laravel.
2. Gunakan CSRF protection bawaan Laravel.
3. Gunakan Laravel Validation untuk seluruh input.
4. Gunakan Eloquent ORM untuk menghindari SQL Injection.
5. Terapkan authorization pada proses penghapusan postingan.
6. Validasi tipe dan ukuran file gambar.
7. Gunakan nama file unik ketika menyimpan gambar.
8. Jangan mempercayai user_id dari input form untuk menentukan pemilik data.
9. Gunakan user yang sedang login melalui Auth::user() atau Auth::id().
10. Pastikan seluruh fitur interaksi hanya dapat digunakan pengguna yang sudah login.

---

## 11. Struktur Folder yang Disarankan

app/

- Models/
  - User.php
  - Post.php
  - Like.php
  - Comment.php
- Http/
  - Controllers/
    - Auth/
    - FeedController.php
    - PostController.php
    - LikeController.php
    - CommentController.php
    - ProfileController.php
    - PasswordController.php

resources/views/

- layouts/
  - app.blade.php
- auth/
  - login.blade.php
  - register.blade.php
- feed/
  - index.blade.php
- profile/
  - index.blade.php
  - edit.blade.php
- settings/
  - password.blade.php
- components/
  - navbar.blade.php
  - post-card.blade.php
  - comment-item.blade.php

database/migrations/

- create_users_table
- create_posts_table
- create_likes_table
- create_comments_table

public/

- assets/

storage/app/public/

- avatars/
- posts/
- comments/

Struktur dapat disesuaikan dengan standar Laravel selama pemisahan tanggung jawab MVC tetap terjaga.

---

## 12. Acceptance Criteria (Kriteria Keberhasilan)

Aplikasi dinyatakan berhasil apabila seluruh kriteria berikut terpenuhi.

### Authentication

- [ ] User dapat melakukan registrasi.
- [ ] Email tidak boleh duplikat.
- [ ] Password di-hash.
- [ ] Setelah registrasi langsung masuk ke Feed.
- [ ] User dapat login.
- [ ] User dapat logout.
- [ ] Guest tidak dapat membuka halaman yang dilindungi.
- [ ] User yang sudah login tidak dapat membuka halaman login/register.

### Profile

- [ ] User dapat melihat profil sendiri.
- [ ] User dapat mengubah nama.
- [ ] User dapat mengubah bio.
- [ ] User dapat mengunggah avatar.
- [ ] Avatar tampil di navbar.
- [ ] Avatar tampil di postingan dan komentar.
- [ ] User dapat mengubah password dengan verifikasi password lama.

### Posts

- [ ] User dapat membuat postingan teks.
- [ ] User dapat membuat postingan gambar.
- [ ] User dapat membuat postingan teks dan gambar.
- [ ] Postingan kosong ditolak.
- [ ] Postingan terbaru tampil paling atas.
- [ ] User hanya dapat menghapus postingan miliknya.

### Likes

- [ ] User dapat memberikan like.
- [ ] User dapat melakukan unlike.
- [ ] Satu user tidak dapat memberikan like ganda.
- [ ] Jumlah like sesuai dengan data database.

### Comments

- [ ] User dapat membuat komentar.
- [ ] User dapat melampirkan gambar pada komentar.
- [ ] Komentar menampilkan avatar pengirim.
- [ ] Komentar menampilkan nama pengirim.
- [ ] Komentar menampilkan waktu pengiriman.

### Database & Security

- [ ] Empat tabel utama berhasil dibuat.
- [ ] Foreign key berjalan dengan benar.
- [ ] Unique constraint likes berjalan.
- [ ] Relasi Eloquent berjalan.
- [ ] Validasi file gambar berjalan.
- [ ] Authorization berjalan.
- [ ] Tidak terdapat celah akses terhadap data milik pengguna lain.

---

## 13. Development Priority

Kerjakan aplikasi secara bertahap dengan urutan berikut:

**Phase 1 – Project Setup**

- Membuat project Laravel.
- Konfigurasi database.
- Menyiapkan Bootstrap.
- Membuat layout utama.

**Phase 2 – Database**

- Membuat migration.
- Membuat model.
- Membuat Eloquent Relationships.
- Memastikan foreign key dan unique constraint.

**Phase 3 – Authentication**

- Registrasi.
- Auto-login setelah registrasi.
- Login.
- Logout.
- Middleware protection.

**Phase 4 – Profile**

- Menampilkan profil.
- Edit profil.
- Upload avatar.
- Change password.

**Phase 5 – Social Feed**

- Menampilkan feed.
- Create post.
- Upload gambar.
- Delete post.

**Phase 6 – Social Interaction**

- Toggle like.
- Menghitung jumlah like.
- Create comment.
- Upload gambar komentar.

**Phase 7 – Testing**

- Menguji seluruh alur aplikasi.
- Menguji validasi.
- Menguji hak akses.
- Menguji relasi database.
- Memastikan tampilan responsive.

---

## 14. Instruksi Khusus untuk Antigravity AI

Bertindak sebagai Senior Laravel Developer yang membantu membangun aplikasi ConnectSpace sesuai dokumen PRD ini.

Ketentuan pengerjaan:

1. Ikuti seluruh requirement yang tercantum pada PRD.
2. Gunakan Laravel MVC dengan Blade dan Bootstrap 5.
3. Jangan menambahkan fitur yang tidak diminta.
4. Jangan membuat arsitektur yang terlalu kompleks.
5. Gunakan Eloquent ORM dan Laravel Validation.
6. Pastikan migration, model, controller, route, dan view saling terhubung.
7. Gunakan Bootstrap CDN jika belum tersedia konfigurasi frontend.
8. Gunakan Laravel Storage untuk file upload.
9. Pastikan seluruh form memiliki CSRF token.
10. Gunakan flash message untuk notifikasi sukses dan gagal.
11. Pastikan tidak ada route, tombol, atau form yang hanya berupa tampilan tanpa fungsi.
12. Jangan menggunakan data dummy sebagai pengganti database pada fitur utama.
13. Jangan membuat ulang project jika project Laravel sudah tersedia.
14. Sebelum menyelesaikan satu tahap, pastikan implementasinya berjalan dan tidak menghasilkan error.
15. Jangan menghapus atau mengubah kode yang sudah berjalan tanpa alasan teknis yang jelas.

### Cara Pengerjaan

Kerjakan secara bertahap sesuai Development Priority.

Jangan langsung membuat seluruh aplikasi dalam satu proses tanpa melakukan pemeriksaan.

Setiap tahap harus:

- Mengimplementasikan kebutuhan.
- Memeriksa konsistensi kode.
- Memastikan route dan view terhubung.
- Memeriksa error.
- Memastikan tahap tersebut dapat digunakan sebelum melanjutkan.

**Final Goal:** Menghasilkan aplikasi media sosial ConnectSpace yang dapat dijalankan secara lokal, menggunakan database MySQL, memiliki autentikasi yang benar, serta seluruh fitur postingan, like, komentar, dan profil berjalan sesuai requirement UJK.
