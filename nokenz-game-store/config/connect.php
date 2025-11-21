<?php
// Cegah warning jika file include berkali-kali
if (isset($conn)) return;

// Konfigurasi Koneksi Database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "nokenz_store";

// Koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set Charset agar tidak error jika simpan emoji / UTF-8
mysqli_set_charset($conn, "utf8");
?>
