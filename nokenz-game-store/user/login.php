<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: home.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body class="auth-page">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card auth-card shadow-lg p-4" style="width: 480px;">
        
        <!-- BACK BUTTON -->
        <a href="../index.php" class="btn btn-sm btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Back
        </a>

        <!-- AUTH ICON -->
        <div class="auth-icon">
            <i class="bi bi-box-arrow-in-right"></i>
        </div>

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h3 class="fw-bold auth-header mb-2">Welcome Back!</h3>
            <p class="text-muted">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- ALERT -->
        <div id="alert"></div>

        <!-- FORM -->
        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-person-fill text-primary"></i> Username
                </label>
                <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-lock-fill text-primary"></i> Password
                </label>
                <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
            </div>

            <button class="btn btn-primary w-100 fw-bold btn-lg mt-3" type="submit">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>

            <div class="mt-4 text-center">
                <p class="mb-0 text-muted">
                    Belum punya akun? <a href="register.php" class="auth-link">Daftar Sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let form = new FormData(this);
    
    fetch("../api/api.php?action=login", {
        method: "POST",
        body: form
    })
    .then(r => r.json())
    .then(res => {
        let alertBox = document.getElementById("alert");
        
        if (!res.status) {
            alertBox.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i> ${res.message}
                </div>`;
            return;
        }

        alertBox.innerHTML = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> Login berhasil! Mengalihkan...
            </div>`;
        
        setTimeout(() => {
            if (res.role === "admin") {
                window.location.href = "../admin/dashboard.php";
            } else {
                window.location.href = "home.php";
            }
        }, 1000);
    })
    .catch(err => {
        console.error(err);
        document.getElementById("alert").innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Terjadi kesalahan koneksi
            </div>`;
    });
});
</script>

</body>
</html>