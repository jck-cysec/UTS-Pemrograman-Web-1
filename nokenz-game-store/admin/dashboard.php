<?php
include "../config/session.php";
include "../config/connect.php";

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil statistik
$total_games = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM games"));
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user'"));
$total_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));

// Total pendapatan
$revenue_query = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM orders WHERE status='paid'");
$revenue = mysqli_fetch_assoc($revenue_query)['total'] ?? 0;

// Pending orders
$pending_orders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='pending'"));

// Data untuk grafik - Orders per status (hanya paid dan pending)
$orders_paid = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='paid'"));
$orders_pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders WHERE status='pending'"));

// Data untuk grafik - Revenue dari recent orders (10 terakhir)
$recent_orders_chart = mysqli_query($conn, 
    "SELECT o.id, o.total_harga, o.tanggal_order, o.status
     FROM orders o 
     ORDER BY o.id DESC 
     LIMIT 10"
);

$order_ids = [];
$order_revenues = [];
$order_colors = [];

while ($row = mysqli_fetch_assoc($recent_orders_chart)) {
    $order_ids[] = '#' . $row['id'];
    $order_revenues[] = $row['total_harga'];
    // Warna berdasarkan status
    if ($row['status'] == 'paid') {
        $order_colors[] = 'rgba(40, 167, 69, 0.8)';
    } elseif ($row['status'] == 'pending') {
        $order_colors[] = 'rgba(255, 193, 7, 0.8)';
    } else {
        $order_colors[] = 'rgba(220, 53, 69, 0.8)';
    }
}

// Reverse array agar urutan dari lama ke baru
$order_ids = array_reverse($order_ids);
$order_revenues = array_reverse($order_revenues);
$order_colors = array_reverse($order_colors);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<!-- SIDEBAR + CONTENT -->
<div class="container-fluid">
    <div class="row">
        
        <!-- SIDEBAR -->
        <div class="col-md-2 bg-dark text-white p-4" style="min-height: 100vh;">
            <h5 class="mb-4"><i class="bi bi-list-ul"></i> Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white active" href="dashboard.php">
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
                    <a class="nav-link text-white" href="news_list.php">
                        <i class="bi bi-newspaper"></i> Manage News
                    </a>
                </li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-4">
            <h2 class="fw-bold mb-4">
                <i class="bi bi-house-door"></i> Dashboard Overview
            </h2>

            <!-- STATISTICS CARDS -->
            <div class="row">
                
                <!-- Total Games -->
                <div class="col-md-3 mb-4">
                    <div class="stat-card bg-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">Total Games</p>
                                <h3><?= $total_games ?></h3>
                            </div>
                            <div style="font-size: 3rem;">
                                <i class="bi bi-controller"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="col-md-3 mb-4">
                    <div class="stat-card bg-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">Total Users</p>
                                <h3><?= $total_users ?></h3>
                            </div>
                            <div style="font-size: 3rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-md-3 mb-4">
                    <div class="stat-card bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">Total Orders</p>
                                <h3><?= $total_orders ?></h3>
                            </div>
                            <div style="font-size: 3rem;">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="col-md-3 mb-4">
                    <div class="stat-card bg-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1">Revenue (Paid)</p>
                                <h3 style="font-size: 1.5rem;">Rp <?= number_format($revenue, 0, ',', '.') ?></h3>
                            </div>
                            <div style="font-size: 3rem;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- PENDING ORDERS ALERT -->
            <?php if ($pending_orders > 0): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill"></i> Ada <strong><?= $pending_orders ?></strong> order pending yang perlu diproses! 
                <a href="orders_list.php" class="alert-link">Lihat sekarang</a>
            </div>
            <?php endif; ?>

            <!-- CHARTS SECTION -->
            <div class="row mt-4">
                <!-- Order Status Chart -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-pie-chart-fill"></i> Order Status
                            </h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center" style="height: 250px;">
                            <canvas id="orderStatusChart" style="max-width: 200px; max-height: 200px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Revenue Trend Chart -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-graph-up"></i> Recent Orders Revenue
                            </h6>
                        </div>
                        <div class="card-body" style="height: 250px;">
                            <canvas id="revenueTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT ORDERS TABLE -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Recent Orders (Last 10)
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent = mysqli_query($conn, 
                                "SELECT o.*, u.nama 
                                 FROM orders o 
                                 JOIN users u ON o.user_id = u.id 
                                 ORDER BY o.id DESC 
                                 LIMIT 10"
                            );
                            
                            while ($row = mysqli_fetch_assoc($recent)):
                                $badge = $row['status'] == 'paid' ? 'bg-success' : 'bg-warning text-dark';
                            ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td><span class="badge <?= $badge ?>"><?= strtoupper($row['status']) ?></span></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal_order'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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
// Data dari PHP
const ordersPaid = <?= $orders_paid ?>;
const ordersPending = <?= $orders_pending ?>;

const orderIds = <?= json_encode($order_ids) ?>;
const orderRevenues = <?= json_encode($order_revenues) ?>;
const orderColors = <?= json_encode($order_colors) ?>;

// Chart 1: Order Status (Doughnut Chart - hanya Paid & Pending)
const ctx1 = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusChart = new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending'],
        datasets: [{
            data: [ordersPaid, ordersPending],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(255, 193, 7, 1)'
            ],
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        weight: 'bold'
                    },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += context.parsed + ' orders';
                        return label;
                    }
                }
            }
        },
        animation: {
            animateScale: true,
            animateRotate: true,
            duration: 1500,
            easing: 'easeOutQuart'
        }
    }
});

// Chart 2: Recent Orders Revenue (Line Chart dengan rounded)
const ctx2 = document.getElementById('revenueTrendChart').getContext('2d');
const revenueTrendChart = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: orderIds,
        datasets: [{
            label: 'Order Amount',
            data: orderRevenues,
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderColor: 'rgba(102, 126, 234, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: orderColors,
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 7,
            pointHoverBorderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                },
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: {
                    size: 13,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 12
                },
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value / 1000) + 'K';
                    },
                    font: {
                        size: 10
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 10
                    }
                },
                grid: {
                    display: false
                }
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});
</script>

</body>
</html>