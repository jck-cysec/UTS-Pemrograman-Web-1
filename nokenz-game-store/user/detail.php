<?php
include "../config/auth_user.php";
include "../config/connect.php";
$game_id = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Game - Nokenz Game Store</title>
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
            <a href="cart.php" class="btn btn-warning me-2"><i class="bi bi-cart"></i> Cart</a>
            <a href="../api/api.php?action=logout" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <a href="home.php" class="btn btn-secondary back-btn"><i class="bi bi-arrow-left"></i> Back</a>
    <div id="detailContainer"></div>
</div>

<footer class="bg-dark text-white text-center py-4">
    <p class="mb-1">© 2025 23552011072_HaidirZacky_CNS B</p>
</footer>

<script>
fetch("../api/api.php?action=get_game&id=<?= $game_id ?>")
    .then(res => res.json())
    .then(g => {
        let html = `
            <div class="row">
                <div class="col-md-5">
                    <img src="../assets/images/games/${g.gambar}" class="game-image shadow">
                </div>
                <div class="col-md-7">
                    <h2 class="fw-bold">${g.nama_game}</h2>
                    <p class="text-muted">${g.genre} • ${g.platform}</p>
                    <h4 class="text-success fw-bold">Rp ${g.harga}</h4>
                    <p class="mt-3">${g.deskripsi}</p>
                    <button class="btn btn-warning btn-lg mt-3" onclick="addCart(${g.id})">
                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>`;
        document.getElementById("detailContainer").innerHTML = html;
    });

function addCart(id) {
    let formData = new FormData();
    formData.append("game_id", id);
    fetch("../api/api.php?action=add_cart", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(r => {
        if (r.status) {
            alert("Game berhasil ditambahkan ke keranjang!");
        } else {
            alert("Gagal menambahkan. Silakan coba lagi.");
        }
    });
}
</script>
</body>
</html>