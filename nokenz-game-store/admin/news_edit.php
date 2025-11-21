<?php
include "../config/session.php";

// Cek admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$news_id = $_GET['id'] ?? 0;

if ($news_id == 0) {
    header("Location: news_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News - Admin</title>
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
                    <a class="nav-link text-white" href="game_list.php">
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
                    <a class="nav-link text-white active" href="news_list.php">
                        <i class="bi bi-newspaper"></i> Manage News
                    </a>
                </li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-4">
            
            <h2 class="fw-bold mb-4">
                <i class="bi bi-pencil-square"></i> Edit News Article
            </h2>

            <!-- Alert Box -->
            <div id="alertBox"></div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-5">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading news data...</p>
            </div>

            <!-- FORM EDIT NEWS -->
            <div class="card shadow-sm" id="editFormCard" style="display: none;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Edit Article Information</h5>
                </div>
                <div class="card-body">
                    <form id="editNewsForm">
                        
                        <input type="hidden" name="id" id="newsId">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Title *</label>
                            <input type="text" class="form-control" name="judul" id="judul" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description *</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="5" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date *</label>
                            <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Image Filename *</label>
                            <input type="text" class="form-control" name="gambar" id="gambar" required>
                            <small class="text-muted">Current image will be replaced if changed</small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="news_list.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-save"></i> Update Article
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
const newsId = <?= $news_id ?>;

// LOAD NEWS DATA
document.addEventListener('DOMContentLoaded', function() {
    loadNewsData();
});

function loadNewsData() {
    fetch(`../api/api.php?action=get_news_by_id&id=${newsId}`)
        .then(res => res.json())
        .then(news => {
            if (!news || !news.id) {
                showAlert('danger', 'News not found');
                setTimeout(() => {
                    window.location.href = 'news_list.php';
                }, 2000);
                return;
            }

            // Fill form
            document.getElementById('newsId').value = news.id;
            document.getElementById('judul').value = news.judul;
            document.getElementById('deskripsi').value = news.deskripsi;
            document.getElementById('tanggal').value = news.tanggal;
            document.getElementById('gambar').value = news.gambar;

            // Show form
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('editFormCard').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            showAlert('danger', 'Error loading news data');
        });
}

// SUBMIT UPDATE
document.getElementById('editNewsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("../api/api.php?action=update_news", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(r => {
        if (r.status) {
            showAlert('success', '✓ News updated successfully!');
            
            // Redirect after 1.5 seconds
            setTimeout(() => {
                window.location.href = "news_list.php";
            }, 1500);
        } else {
            showAlert('danger', '✗ Failed to update news: ' + r.message);
        }
    })
    .catch(err => {
        console.error(err);
        showAlert('danger', '✗ Error occurred');
    });
});

function showAlert(type, message) {
    const alertBox = document.getElementById('alertBox');
    alertBox.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    
    setTimeout(() => {
        alertBox.innerHTML = '';
    }, 5000);
}
</script>

</body>
</html>