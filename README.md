# 🎮 Nokenz Game Store - UTS Pemrograman Web 1

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Laragon](https://img.shields.io/badge/LocalServer-Laragon-1996EA?style=flat-square)
![Postman](https://img.shields.io/badge/API_Testing-Postman-FF6C37?style=flat-square&logo=postman&logoColor=white)

Proyek ini merupakan implementasi dari **Ujian Tengah Semester -
Pemrograman Web 1 (3 SKS)** di **Universitas Teknologi Bandung (UTB)**.

-   **Dosen Pengampu:** Nova Agustina, S.T., M.Kom
-   **Kelas:** TIF RP 23 CNS B
-   **Mahasiswa:** 23552011072 - Haidir Mirza Ahmad Zacky

------------------------------------------------------------------------

## 🚀 Teknologi yang Digunakan

  -----------------------------------------------------------------------
  Kategori            Teknologi                Keterangan
  ------------------- ------------------------ --------------------------
  **Frontend**        HTML5, CSS3, JavaScript  Tampilan dan interaksi
                                               Website

  **CSS Framework**   Bootstrap 5              Untuk tampilan UI
                                               responsif

  **Backend**         PHP Native               Mengelola logika API

  **Database**        MySQL / MariaDB          Penyimpanan Data

  **API Testing**     Postman / Bruno          Uji semua endpoint API

  **Local Server**    Laragon                  Menjalankan PHP & Database
  -----------------------------------------------------------------------

------------------------------------------------------------------------

## 💻 Project 1 - Website "Nokenz Game Store"

Website dibangun menggunakan HTML, CSS, JavaScript, dan Bootstrap 5.
Semua data diambil dari API.

### 🔑 Fitur Website

-   Halaman Home
-   Halaman Detail (Game & Berita)
-   Login dengan Validasi JavaScript
-   Registrasi
-   Dashboard User
-   Footer wajib pada semua halaman

------------------------------------------------------------------------

## 🛠️ Project 2 - CRUD API (PHP Native)

Semua endpoint berada pada file:

    api/api.php

Menggunakan parameter:

    ?action=<nama_action>

### Endpoints Utama

-   **Autentikasi**: register, login, logout\
-   **Public Data**: get_games, get_game, get_news, get_genres,
    get_platforms\
-   **CRUD Game (Admin)**: insert_game, update_game, delete_game\
-   **Cart & Order (User)**: add_cart, get_cart, checkout,
    order_history, payment_confirm

------------------------------------------------------------------------

## 📸 Screenshot Proyek

### **I. Website**

#### 🏠 **Landing Page**  
![Home](nokenz-game-store/UI/1.png)

#### 🔐 **Login**  
![Login](nokenz-game-store/UI/5.png)

#### 🏠 **Home**  
![Home](nokenz-game-store/UI/16.png)
  
#### 🧑‍💼 **Dashboard**  
![Dashboard](nokenz-game-store/UI/6.png)

---

### **II. API Testing (Postman / Bruno)**

#### 🔑 **Login Admin**  
![API Login Admin](nokenz-game-store/Postmancrud/2.png)

#### 🎮 **Get All Game**  
![API Get All Game](nokenz-game-store/Postmancrud/1.png)

#### ➕ **Insert Game**  
![API Insert Game](nokenz-game-store/Postmancrud/3.png)

#### ✏️ **Update Game**  
![API Update Game](nokenz-game-store/Postmancrud/4.png)

#### ❌ **Delete Game**  
![API Delete Game](nokenz-game-store/Postmancrud/5.png)

------------------------------------------------------------------------
