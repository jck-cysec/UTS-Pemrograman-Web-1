# 🎮 Nokenz Game Store - Tugas UTS Pemrograman Web 1

Proyek ini merupakan implementasi dari Ujian Tengah Semeter (UTS) mata kuliah **Pemrograman Web 1 (3 SKS)** di **Universitas Teknologi Bandung (UTB)**.

* **Dosen Pengampu:** Nova Agustina, S.T., M.Kom.
* **Kelas:** TIF RP 23 CNS B
* **Mahasiswa:** 23552011072 - Haidir Zacky

Proyek ini terdiri dari dua bagian utama: Pengembangan **Website Toko Game** dan implementasi **CRUD API** sebagai *backend* data menggunakan PHP dan MySQL.

***

## 🚀 Teknologi yang Digunakan

| Kategori | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Frontend/Web** | HTML5, CSS3, **JavaScript** | Bahasa dasar pengembangan web. |
| **CSS Framework** | **Bootstrap 5** | Digunakan untuk desain yang menarik dan responsif. |
| **Backend API** | **PHP** (Native) | Mengelola *routing* dan logika bisnis melalui `api/api.php`. |
| **Database** | **MySQL / MariaDB** | Digunakan untuk penyimpanan data Game, User, Order, dll. |

***

## 💻 Project 1: Pengembangan Website "Nokenz Game Store"

Website ini dikembangkan sesuai kriteria UTS, berinteraksi penuh dengan API **Project 2** sebagai sumber data.

### Fitur Utama Website (Sesuai Soal Ujian)

1.  **Halaman Utama (Home)**: Menampilkan informasi toko, daftar Game, dan Berita Terbaru.
2.  **Halaman Detail Informasi**: Detail Game/Berita (sesuai pilihan pengguna di Halaman Utama).
3.  **Halaman Login** (`user/login.php`).
4.  **Halaman Registrasi** (`user/register.php`).
5.  **Halaman Menu Utama (Dashboard)**: Tampil setelah user berhasil Login.

### Fungsionalitas Kunci Frontend

* **Validasi Login (Wajib):** Menggunakan fungsi **JavaScript** dengan **Struktur Percabangan If** untuk validasi input kredensial sebelum memanggil API `/login`.
* **Interaksi API:** Menggunakan JavaScript `fetch()` untuk semua operasi data (Game, Filter, Cart, Order, dll.).
* **Footer Kustom:** Semua halaman mencantumkan Footer dengan format wajib: **`@Copyright by 23552011072_HaidirZacky_CNS B`**.

***

## 🛠️ Project 2: Dokumentasi CRUD API (PHP Native)

API dikembangkan dalam file tunggal **`api/api.php`**. Semua aksi diakses melalui parameter **`?action=...`**.

### A. Endpoint Autentikasi & User (Wajib Uji)

| Action | Metode | Keterangan | Request Body (x-www-form-urlencoded) |
| :--- | :--- | :--- | :--- |
| `register` | `POST` | Pendaftaran User baru (role default: 'user'). | `nama`, `email`, `username`, `password` |
| **`login`** | **`POST`** | Autentikasi User/Admin. | **`username`, `password`** |
| `logout` | `GET` | Menghapus sesi login. | - |

### B. Endpoint Data Game & Publik

| Action | Metode | Keterangan | Query Parameters |
| :--- | :--- | :--- | :--- |
| **`get_games`** | **`GET`** | **READ:** Mendapatkan semua daftar Game. | - |
| `get_game` | `GET` | Mendapatkan detail Game berdasarkan ID. | `id` |
| `get_news` | `GET` | Mendapatkan semua daftar Berita. | - |
| `get_genres` | `GET` | Mendapatkan daftar Genre dari tabel `genres`. | - |
| `get_platforms` | `GET` | Mendapatkan daftar Platform dari tabel `platforms`. | - |

### C. Endpoint CRUD Game (Admin Only - Wajib Uji)

Operasi ini memerlukan *session* `role='admin'`.

| Action | Metode | Keterangan | Request Body (x-www-form-urlencoded) |
| :--- | :--- | :--- | :--- |
| **`insert_game`** | **`POST`** | **CREATE:** Menambahkan Game baru. | `nama_game`, `genre`, `platform`, `harga`, `deskripsi`, `gambar` |
| `update_game` | `POST` | **UPDATE:** Memperbarui data Game. | `id`, `nama_game`, `genre`, `platform`, `harga`, `deskripsi`, `gambar` |
| **`delete_game`** | **`POST`** | **DELETE:** Menghapus Game. | **`id`** (Game ID) |

### D. Endpoint Cart & Order (User Logged In)

| Action | Metode | Keterangan | Request Body |
| :--- | :--- | :--- | :--- |
| `add_cart` | `POST` | Menambahkan Game ke keranjang. | `game_id` |
| `get_cart` | `GET` | Mendapatkan isi keranjang user. | - |
| `delete_cart_item` | `POST` | Menghapus item dari keranjang. | `cart_id` |
| **`checkout`** | **`POST`** | Membuat Order dari Cart, menghitung total, dan menghapus Cart. | - |
| `payment_confirm` | `POST` | Mengubah status Order menjadi 'paid'. | `order_id`, `method` |
| `order_history` | `GET` | Riwayat Order User. | - |

***

## 📸 Bukti Pengerjaan (Screenshots)

**(CATATAN: Tempatkan *screenshot* dari Project Web dan hasil uji API dari Postman/Bruno di bawah ini. Pastikan *screenshot* API menunjukkan **Endpoint URL**, **Metode**, **Body Request (untuk POST)**, dan **Response/Status Code**)**

### I. Screenshot Proyek Web (Project 1)

**A. Halaman Utama Website**


**B. Halaman Login dengan Validasi JavaScript**


**C. Halaman Menu Utama (Dashboard)**


### II. Hasil Uji API (Project 2 - POSTMAN/BRUNO)

#### 1. Uji Autentikasi: Login (POST)
**Endpoint:** `[BASE_URL]/api/api.php?action=login`


#### 2. Uji CRUD: CREATE Game (POST)
**Endpoint:** `[BASE_URL]/api/api.php?action=insert_game`


#### 3. Uji CRUD: READ Game (GET)
**Endpoint:** `[BASE_URL]/api/api.php?action=get_games`


#### 4. Uji Transaksi: Checkout (POST)
**Endpoint:** `[BASE_URL]/api/api.php?action=checkout`


#### 5. Uji CRUD: DELETE Game (POST)
**Endpoint:** `[BASE_URL]/api/api.php?action=delete_game`


***

## 🔗 Repository

* **Dibuat oleh:** 23552011072 - Haidir Zacky - TIF RP 23 CNS B
* **Dosen Pengampu:** Nova Agustina, S.T., M.Kom.
* **Link Repository GitHub:** `[URL_GITHUB_ANDA]`
