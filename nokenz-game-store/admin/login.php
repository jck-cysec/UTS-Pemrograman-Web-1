<?php
session_start();

// Jika sudah login, redirect
if (isset($_SESSION['logged_in']) && $_SESSION['role'] == 'admin') {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .login-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .login-card:hover .auth-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }

        .auth-icon i {
            font-size: 3rem;
            color: white;
        }
        
        .back-btn {
            position: absolute;
            top: 15px;
            left: 15px;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card login-card p-5 position-relative" style="width: 500px;">

        <!-- BACK BUTTON -->
        <a href="../index.php" class="btn btn-sm btn-outline-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Back
        </a>

        <!-- AUTH ICON -->
        <div class="auth-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <!-- HEADER -->
        <div class="text-center mb-4">
            <h3 class="fw-bold login-header mb-2">Admin Login</h3>
            <p class="text-muted">Masukkan kredensial admin untuk melanjutkan</p>
        </div>

        <!-- ALERT -->
        <div id="alert"></div>

        <!-- FORM -->
        <form id="adminLoginForm">

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-person-fill text-primary"></i> Username
                </label>
                <input type="text" class="form-control" name="username" placeholder="Admin username" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    <i class="bi bi-lock-fill text-primary"></i> Password
                </label>
                <input type="password" class="form-control" name="password" placeholder="Admin password" required>
            </div>

            <button class="btn btn-warning w-100 fw-bold btn-lg mt-3" type="submit">
                <i class="bi bi-shield-check"></i> Login as Admin
            </button>

            <div class="mt-4 text-center">
                <small class="text-muted">
                    Bukan admin? <a href="../user/login.php" class="text-decoration-none fw-bold" style="color: #667eea;">User Login</a>
                </small>
            </div>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("adminLoginForm").addEventListener("submit", function(e) {
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

        // Cek apakah role adalah admin
        if (res.role === "admin") {
            alertBox.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> Login berhasil! Mengalihkan ke dashboard...
                </div>`;
            
            setTimeout(() => {
                window.location.href = "dashboard.php";
            }, 1000);
        } else {
            alertBox.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-shield-x"></i> Akses ditolak! Hanya admin yang diizinkan.
                </div>`;
        }

    })
    .catch(err => {
        console.error(err);
        document.getElementById("alert").innerHTML = 
            `<div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> Terjadi kesalahan koneksi
            </div>`;
    });
});
</script>

</body>
</html>