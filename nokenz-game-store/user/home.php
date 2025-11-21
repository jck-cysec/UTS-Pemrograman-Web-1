<?php
include "../config/auth_user.php";
$user_id = $_SESSION['user_id'];
include "../config/connect.php";
$q = mysqli_query($conn, "SELECT nama FROM users WHERE id='$user_id'");
$u = mysqli_fetch_assoc($q);
$nama = $u['nama'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">
            <i class="bi bi-controller"></i> Nokenz Game Store
        </a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Hi, <strong><?= htmlspecialchars($nama) ?></strong></span>
            <a href="cart.php" class="btn btn-warning me-2"><i class="bi bi-cart"></i> Cart</a>
            <a href="order_history.php" class="btn btn-info me-2"><i class="bi bi-clock-history"></i> History</a>
            <a href="profile.php" class="btn btn-primary me-2"><i class="bi bi-person"></i> Profile</a>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="filter-section">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold"><i class="bi bi-search"></i> Search Game</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama game..." onkeyup="applyFilters()">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="bi bi-collection"></i> Genre</label>
                <select class="form-select" id="genreFilter" onchange="applyFilters()">
                    <option value="">All Genres</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold"><i class="bi bi-laptop"></i> Platform</label>
                <select class="form-select" id="platformFilter" onchange="applyFilters()">
                    <option value="">All Platforms</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold"><i class="bi bi-sort-down"></i> Sort</label>
                <select class="form-select" id="sortSelect" onchange="applyFilters()">
                    <option value="newest">Newest</option>
                    <option value="cheapest">Cheapest</option>
                    <option value="expensive">Most Expensive</option>
                    <option value="a-z">A - Z</option>
                    <option value="z-a">Z - A</option>
                </select>
            </div>
        </div>
        <div id="activeFilters" class="mt-3"></div>
        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="resetFilters()">
            <i class="bi bi-arrow-clockwise"></i> Reset All Filters
        </button>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0"><i class="bi bi-collection-play"></i> Daftar Game</h3>
        <span class="badge bg-dark" id="gameCount">Loading...</span>
    </div>

    <div class="row" id="gameContainer">
        <div class="loading-spinner">
            <div class="spinner-border text-warning"></div>
            <p class="text-muted mt-2">Loading games...</p>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4">
    <p class="mb-1">© 2025 23552011072_HaidirZacky_CNS B</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/user_home.js"></script>
</body>
</html>