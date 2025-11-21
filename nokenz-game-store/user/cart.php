<?php
include "../config/auth_user.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Nokenz Game Store</title>
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
        <div class="d-flex">
            <a href="home.php" class="btn btn-secondary me-2"><i class="bi bi-arrow-left"></i> Back</a>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-cart"></i> Keranjang Saya</h3>
    <div id="cartList"></div>
    <hr>
    <div class="d-flex justify-content-between align-items-center">
        <h4>Total:</h4>
        <h4 class="text-success fw-bold" id="totalHarga">Rp 0</h4>
    </div>
    <button class="btn btn-warning btn-lg w-100 mt-4" onclick="goCheckout()">
        <i class="bi bi-credit-card"></i> Lanjut Checkout
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
loadCart();
function loadCart() {
    fetch("../api/api.php?action=get_cart")
        .then(res => res.json())
        .then(data => {
            let html = "";
            let total = 0;
            if (data.length === 0) {
                document.getElementById("cartList").innerHTML =
                    `<p class="text-muted">Keranjang kamu masih kosong.</p>`;
                document.getElementById("totalHarga").innerText = "Rp 0";
                return;
            }
            data.forEach(item => {
                total += Number(item.harga);
                html += `
                    <div class="cart-card bg-white p-3 shadow-sm mb-3">
                        <div class="d-flex align-items-center">
                            <img src="../assets/images/games/${item.gambar}" class="cart-img me-3">
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">${item.nama_game}</h5>
                                <p class="text-muted mb-1">${item.genre} • ${item.platform}</p>
                                <p class="text-success fw-bold mb-0">Rp ${item.harga}</p>
                            </div>
                            <button class="btn btn-danger btn-sm" onclick="deleteItem(${item.cart_id})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>`;
            });
            document.getElementById("cartList").innerHTML = html;
            document.getElementById("totalHarga").innerText = "Rp " + total;
        });
}
function deleteItem(cart_id) {
    let formData = new FormData();
    formData.append("cart_id", cart_id);
    fetch("../api/api.php?action=delete_cart_item", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(r => {
        if (r.status) loadCart();
        else alert("Gagal menghapus item");
    });
}
function goCheckout() {
    window.location.href = "checkout.php";
}
</script>
</body>
</html>