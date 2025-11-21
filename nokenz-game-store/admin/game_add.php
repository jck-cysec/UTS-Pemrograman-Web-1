<?php
include "../config/session.php";
include "../config/connect.php";

// Cek admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// =========================
// LOAD GENRES (ambil dari tabel games)
// =========================
$genres = [];
$q_genre = mysqli_query($conn, "SELECT DISTINCT genre FROM games ORDER BY genre ASC");
while ($g = mysqli_fetch_assoc($q_genre)) {
    if (!empty($g['genre'])) {
        $genres[] = $g['genre'];
    }
}

// =========================
// LOAD PLATFORMS (ambil dari tabel games)
// =========================
$platforms = [];
$q_platform = mysqli_query($conn, "SELECT DISTINCT platform FROM games ORDER BY platform ASC");
while ($p = mysqli_fetch_assoc($q_platform)) {
    if (!empty($p['platform'])) {
        $platforms[] = $p['platform'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Game - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="bi bi-shield-fill-check"></i> NOKENZ Admin Panel
        </a>
        <div class="d-flex align-items-center">
            <a href="game_list.php" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <span class="text-white me-3">Hi, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></span>
            <a href="logout.php" class="btn btn-outline-light">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        
        <!-- SIDEBAR -->
        <div class="col-md-2 bg-dark text-white p-4" style="min-height: 100vh;">
            <h5 class="mb-4"><i class="bi bi-list-ul"></i> Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white active" href="game_list.php">
                        <i class="bi bi-controller"></i> Manage Games
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="category_manage.php">
                        <i class="bi bi-tags"></i> Manage Categories
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="users_list.php">
                        <i class="bi bi-people"></i> Manage Users
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="orders_list.php">
                        <i class="bi bi-box-seam"></i> Manage Orders
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="news_list.php">
                        <i class="bi bi-newspaper"></i> Manage News
                    </a>
                </li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-4">
            
            <h2 class="fw-bold mb-4">
                <i class="bi bi-plus-circle"></i> Add New Game
            </h2>

            <div id="alertBox"></div>

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-joystick"></i> Game Information</h5>
                </div>
                <div class="card-body">
                    <form id="addGameForm">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Game Name *</label>
                            <input type="text" class="form-control" name="nama_game" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Genre *</label>
                                <select class="form-select" name="genre" id="genre" required>
                                    <option value="">-- Select Genre --</option>
                                    <?php foreach ($genres as $g): ?>
                                        <option value="<?= $g ?>"><?= $g ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">
                                    Manage category → <a href="category_manage.php" target="_blank">Click here</a>
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Platform *</label>
                                <select class="form-select" name="platform" id="platform" required>
                                    <option value="">-- Select Platform --</option>
                                    <?php foreach ($platforms as $p): ?>
                                        <option value="<?= $p ?>"><?= $p ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">
                                    Manage category → <a href="category_manage.php" target="_blank">Click here</a>
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Price (Rp) *</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description *</label>
                            <textarea class="form-control" name="deskripsi" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Image Filename *</label>
                            <input type="text" class="form-control" name="gambar" placeholder="e.g. game1.jpg" required>
                            <small class="text-muted">Upload image to <code>/assets/images/games/</code> folder first</small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="game_list.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-save"></i> Save Game
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- FOOTER -->
<footer class="text-center py-4 mt-auto">
    <p class="mb-1 text-secondary">
        <i class="bi bi-c-circle"></i> 2025 <strong>NOKENZ Game Store</strong> - Admin Panel
    </p>
    <p class="mb-0">
        <small class="text-muted">23552011072_HaidirZacky_CNS B</small>
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// LOAD GENRES FROM API
function loadGenres() {
    fetch("../api/api.php?action=get_genres")
    .then(r => r.json())
    .then(list => {
        let sel = document.getElementById("genre");
        sel.innerHTML = '<option value="">-- Select Genre --</option>';

        list.forEach(name => {
            sel.innerHTML += `<option value="${name}">${name}</option>`;
        });
    })
    .catch(err => console.error('Error loading genres:', err));
}

// LOAD PLATFORMS FROM API
function loadPlatforms() {
    fetch("../api/api.php?action=get_platforms")
    .then(r => r.json())
    .then(list => {
        let sel = document.getElementById("platform");
        sel.innerHTML = '<option value="">-- Select Platform --</option>';

        list.forEach(name => {
            sel.innerHTML += `<option value="${name}">${name}</option>`;
        });
    })
    .catch(err => console.error('Error loading platforms:', err));
}

document.addEventListener("DOMContentLoaded", () => {
    loadGenres();
    loadPlatforms();
});

// ADD GAME REQUEST
document.getElementById("addGameForm").addEventListener("submit", e => {
    e.preventDefault();

    let fd = new FormData(e.target);

    fetch("../api/api.php?action=insert_game", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            showAlert("success", "✓ Game added successfully!");
            setTimeout(() => window.location.href = "game_list.php", 1200);
        } else {
            showAlert("danger", "✗ " + res.message);
        }
    })
    .catch(err => {
        console.error(err);
        showAlert("danger", "✗ Error occurred");
    });
});

function showAlert(type, msg) {
    document.getElementById("alertBox").innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${msg}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}
</script>

</body>
</html>