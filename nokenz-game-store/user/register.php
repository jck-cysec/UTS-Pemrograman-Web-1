<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body class="auth-page">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding: 2rem 0;">
    <div class="card auth-card shadow-lg p-4" style="width: 480px;">
        
        <!-- BACK BUTTON -->
        <a href="login.php" class="btn btn-sm btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Back
        </a>

        <!-- AUTH ICON -->
        <div class="auth-icon" style="background: var(--accent-gradient);">
            <i class="bi bi-person-plus"></i>
        </div>

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h3 class="fw-bold auth-header mb-2">Buat Akun Baru</h3>
            <p class="text-muted">Daftar untuk mulai berbelanja game</p>
        </div>

        <!-- ALERT -->
        <div id="alert"></div>

        <!-- FORM -->
        <form id="registerForm">
            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-person-fill text-warning"></i> Nama Lengkap
                </label>
                <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-envelope-fill text-warning"></i> Email
                </label>
                <input type="email" class="form-control" name="email" placeholder="contoh@email.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-person-circle text-warning"></i> Username
                </label>
                <input type="text" class="form-control" name="username" placeholder="Pilih username unik" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-lock-fill text-warning"></i> Password
                </label>
                <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter" required>
            </div>

            <button class="btn btn-warning w-100 fw-bold btn-lg mt-3" type="submit">
                <i class="bi bi-person-plus"></i> Daftar Sekarang
            </button>

            <div class="mt-4 text-center">
                <p class="mb-0 text-muted">
                    Sudah punya akun? <a href="login.php" class="auth-link">Login Sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let form = new FormData(this);
    
    fetch("../api/api.php?action=register", {
        method: "POST",
        body: form
    })
    .then(r => r.json())
    .then(res => {
        let alertBox = document.getElementById("alert");
        
        if (res.status) {
            alertBox.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> ${res.message}
                </div>`;
            
            // Reset form
            this.reset();
            
            setTimeout(() => window.location.href = "login.php", 1500);
        } else {
            alertBox.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i> ${res.message}
                </div>`;
        }
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