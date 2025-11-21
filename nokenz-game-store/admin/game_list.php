<?php
include "../config/session.php";
include "../config/connect.php";

// Cek admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Games - Admin</title>
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
            <span class="text-white me-3">Hi, <strong><?= $_SESSION['nama'] ?? 'Admin' ?></strong></span>
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
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">
                    <i class="bi bi-controller"></i> Manage Games
                </h2>
                <a href="game_add.php" class="btn btn-warning btn-lg">
                    <i class="bi bi-plus-circle"></i> Add New Game
                </a>
            </div>

            <div id="alertBox"></div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Game Name</th>
                                <th>Genre</th>
                                <th>Platform</th>
                                <th>Price</th>
                                <th width="160px">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="gameTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to delete this game?</p>
                <p class="fw-bold" id="gameNameToDelete"></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()">
                    <i class="bi bi-trash"></i> Yes, Delete
                </button>
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
let deleteGameId = 0;
let deleteModal;

document.addEventListener("DOMContentLoaded", () => {
    deleteModal = new bootstrap.Modal(document.getElementById("deleteModal"));
    loadGames();
});

// LOAD GAMES FROM API
function loadGames() {
    fetch("../api/api.php?action=get_games")
    .then(res => res.json())
    .then(data => {
        let html = "";

        if (!data || data.length === 0) {
            html = `<tr><td colspan="7" class="text-center text-muted py-4">No games available</td></tr>`;
        } else {
            data.forEach(game => {
                const safeName = String(game.nama_game).replace(/"/g, '&quot;');

                const img = game.gambar
                    ? `../assets/images/games/${game.gambar}`
                    : "../assets/images/noimage.png";

                html += `
                <tr>
                    <td>${game.id}</td>
                    <td><img src="${img}" class="game-img"></td>
                    <td><strong>${safeName}</strong></td>
                    <td>${game.genre ?? '-'}</td>
                    <td>${game.platform ?? '-'}</td>
                    <td class="text-success fw-bold">Rp ${formatRupiah(game.harga)}</td>
                    <td>
                        <a href="game_edit.php?id=${game.id}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteGame(${game.id}, '${safeName}')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>`;
            });
        }

        document.getElementById("gameTableBody").innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        showAlert("danger", "Failed to load games.");
    });
}

// DELETE CONFIRM
function deleteGame(id, name) {
    deleteGameId = id;
    document.getElementById("gameNameToDelete").innerHTML = name;
    deleteModal.show();
}

function confirmDelete() {
    let fd = new FormData();
    fd.append("id", deleteGameId);

    fetch("../api/api.php?action=delete_game", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        deleteModal.hide();

        if (res.status) {
            showAlert("success", "✓ Game deleted successfully!");
            loadGames();
        } else {
            showAlert("danger", "✗ Failed: " + res.message);
        }
    })
    .catch(err => showAlert("danger", "✗ Error deleting game"));
}

// FORMAT
function showAlert(type, message) {
    const box = document.getElementById("alertBox");
    box.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}

function formatRupiah(angka) {
    return parseInt(angka).toLocaleString("id-ID");
}
</script>

</body>
</html>