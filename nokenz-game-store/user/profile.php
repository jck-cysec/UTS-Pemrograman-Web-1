<?php
include "../config/auth_user.php";
$user_id = $_SESSION['user_id'];
include "../config/connect.php";
$q = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$u = mysqli_fetch_assoc($q);
$nama = $u['nama'];
$email = $u['email'];
$username = $u['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">
            <i class="bi bi-controller"></i> Nokenz Game Store
        </a>
        <div class="d-flex align-items-center">
            <a href="home.php" class="btn btn-secondary me-2"><i class="bi bi-arrow-left"></i> Back</a>
            <span class="text-white me-3">Hi, <strong><?= htmlspecialchars($nama) ?></strong></span>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5" style="max-width: 650px;">
    <div class="profile-card">
        <div class="text-center">
            <div class="avatar">
                <?= strtoupper(substr($nama, 0, 1)) ?>
            </div>
            <h3 class="fw-bold mt-3"><?= htmlspecialchars($nama) ?></h3>
            <p class="text-muted mb-4"><?= htmlspecialchars($email) ?></p>
        </div>

        <hr>

        <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square"></i> Edit Profile</h5>

        <form id="profileForm">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" value="<?= htmlspecialchars($nama) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password Baru (optional)</label>
                <input type="password" class="form-control" id="password" placeholder="Biarkan kosong jika tidak ingin ubah">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
        </form>

        <div id="alertBox" class="mt-3"></div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4">
    <p class="mb-1">© 2025 23552011072_HaidirZacky_CNS B</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("profileForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = {
        action: "update_profile",
        nama: document.getElementById("nama").value,
        username: document.getElementById("username").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
    };
    fetch("../api/api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData)
    })
    .then(r => r.json())
    .then(res => {
        let alert = "";
        if (res.success) {
            alert = `<div class="alert alert-success">✓ ${res.message}</div>`;
        } else {
            alert = `<div class="alert alert-danger">✗ ${res.message}</div>`;
        }
        document.getElementById("alertBox").innerHTML = alert;
    })
    .catch(err => {
        document.getElementById("alertBox").innerHTML =
            `<div class="alert alert-danger">✗ Terjadi kesalahan!</div>`;
        console.error(err);
    });
});
</script>
</body>
</html>