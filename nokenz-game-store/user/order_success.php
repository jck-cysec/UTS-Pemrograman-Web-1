<?php
session_start();
include "../config/connect.php";
include "../config/auth_user.php"; // pastikan user sudah login

$user_id = $_SESSION['user_id'];

// Cek apakah order_id dikirim
if (!isset($_GET['order_id'])) {
    header("Location: home.php");
    exit;
}

$order_id = $_GET['order_id'];

// Ambil detail order dari database
$query = mysqli_query($conn, "
    SELECT g.nama_game, g.harga, o.jumlah
    FROM orders o
    JOIN games g ON o.game_id = g.id
    WHERE o.id='$order_id' AND o.user_id='$user_id'
");

if (mysqli_num_rows($query) == 0) {
    echo "Order tidak ditemukan!";
    exit;
}

$orders = [];
$total = 0;
while ($row = mysqli_fetch_assoc($query)) {
    $orders[] = $row;
    $total += $row['harga'] * $row['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success - Nokenz Game Store</title>
    <link rel="stylesheet" href="style.css"> <!-- sesuaikan path -->
</head>
<body>
    <div class="container">
        <h1>Order Berhasil!</h1>
        <h3>Detail Pesanan:</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>Nama Game</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['nama_game']) ?></td>
                <td>Rp <?= number_format($order['harga'],0,',','.') ?></td>
                <td><?= $order['jumlah'] ?></td>
                <td>Rp <?= number_format($order['harga'] * $order['jumlah'],0,',','.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>Rp <?= number_format($total,0,',','.') ?></strong></td>
            </tr>
        </table>
        <br>
        <a href="home.php"><button>Kembali ke Home</button></a>
        <a href="order_history.php"><button>Lihat Riwayat Order</button></a>
    </div>
</body>
</html>
