# Digital Library Project (Perpustakaan Digital)

Dokumen ini merinci rancangan, alur kerja, dan fitur-fitur dari aplikasi Perpustakaan Digital yang dibangun menggunakan framework Laravel.

## 🚀 Gambaran Proyek
Aplikasi manajemen perpustakaan modern yang memfasilitasi peminjaman buku fisik dan akses ebook secara digital, dilengkapi dengan sistem denda otomatis dan manajemen pengguna.

## 🛠️ Tech Stack
- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **Database:** SQLite (Default)
- **Frontend:** Blade Templating, Tailwind CSS (Vite)
- **Authentication:** Laravel Breeze / Custom Auth

## 📂 Struktur Database

### 1. Users (Pengguna)
- `id`, `name`, `username`, `nim`, `email`, `password`
- `phone`, `address`, `gender`
- `role` (admin, petugas, mahasiswa)
- `is_approved` (boolean untuk validasi pendaftaran)

### 2. Books (Buku)
- `id`, `category_id`, `title`, `slug`, `author`, `publisher`
- `publication_year`, `isbn`, `description`, `stock`, `cover_image`

### 3. Categories (Kategori)
- `id`, `name`

### 4. Loans (Peminjaman)
- `id`, `user_id`, `book_id`
- `loan_date`, `due_date`, `return_date`
- `status` (borrowed, returned, overdue)
- `fine_amount` (jumlah denda jika ada)

## ✨ Fitur Utama

### 1. Dashboard
- **Admin:** Statistik total buku, peminjaman aktif, pengguna baru, dan grafik aktivitas.
- **User:** Ringkasan buku yang sedang dipinjam, riwayat, dan rekomendasi buku.

### 2. Manajemen Buku & Kategori
- CRUD (Create, Read, Update, Delete) Buku.
- Pengelompokan buku berdasarkan kategori.
- Manajemen stok buku secara otomatis saat dipinjam/dikembalikan.

### 3. Ebook
- Katalog ebook digital.
- Fitur baca langsung di aplikasi (PDF Viewer).
- Download ebook (opsional berdasarkan izin).

### 4. Sistem Peminjaman & Denda
- Alur peminjaman buku dengan batas waktu.
- Kalkulasi otomatis denda jika pengembalian terlambat.
- Manajemen status peminjaman (menunggu persetujuan, dipinjam, dikembalikan).

### 5. Laporan (Reports)
- Laporan peminjaman bulanan/mingguan.
- Laporan denda masuk.
- Export laporan ke PDF atau Excel.

### 6. Profil
- Manajemen informasi pribadi pengguna.
- Riwayat aktivitas peminjaman pribadi.
- Ubah password.

## 🔄 Alur Kerja (Workflow)

1. **Registrasi & Approval:** Mahasiswa mendaftar -> Admin menyetujui akun (`is_approved`).
2. **Peminjaman:** Mahasiswa memilih buku -> Mengajukan peminjaman -> Petugas menyetujui -> Stok buku berkurang.
3. **Pengembalian:** Mahasiswa mengembalikan buku -> Petugas memperbarui status -> Cek keterlambatan -> Hitung denda jika ada -> Stok buku bertambah.

## 📝 Catatan Pengembangan
- [x] Inisialisasi Project & Migrasi Database.
- [x] Setup Model & Relationship.
- [ ] Implementasi Manajemen Buku.
- [ ] Implementasi Sistem Peminjaman.
- [ ] Tambahkan kolom `file_path` atau `ebook_url` ke tabel `books` untuk fitur Ebook.
- [ ] Integrasi PDF Viewer untuk Ebook.
- [ ] Fitur Laporan.
