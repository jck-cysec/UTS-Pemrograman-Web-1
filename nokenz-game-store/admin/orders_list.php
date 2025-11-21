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
    <title>Manage Orders - Admin</title>
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
                    <a class="nav-link text-white active" href="orders_list.php">
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
                <i class="bi bi-box-seam"></i> Manage Orders
            </h2>

            <!-- Alert Box -->
            <div id="alertBox"></div>

            <!-- Filter -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Options</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Filter by Status:</label>
                            <select class="form-select" id="filterStatus" onchange="loadOrders()">
                                <option value="all">All Orders</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning" onclick="loadOrders()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ORDERS TABLE -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Orders List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                            <tr>
                                <td colspan="7" class="text-center">
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

<!-- Order Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="bi bi-receipt"></i> Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="bi bi-arrow-repeat"></i> Change Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Change status for Order #<strong id="orderIdStatus"></strong>:</p>
                <select class="form-select" id="newStatus">
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmChangeStatus()">
                    <i class="bi bi-check-circle"></i> Update Status
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
let detailModal, statusModal;
let currentOrderId = 0;

document.addEventListener('DOMContentLoaded', function() {
    detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    loadOrders();
});

// LOAD ORDERS
function loadOrders() {
    const filter = document.getElementById('filterStatus').value;
    
    fetch("../api/api.php?action=get_all_orders")
        .then(res => res.json())
        .then(data => {
            let html = "";

            // Filter data
            let filteredData = filter === 'all' ? data : data.filter(o => o.status === filter);

            if (filteredData.length === 0) {
                html = `<tr><td colspan="7" class="text-center text-muted">No orders found</td></tr>`;
            } else {
                filteredData.forEach(order => {
                    const badge = order.status === 'paid' ? 'bg-success' : 'bg-warning text-dark';
                    const paymentMethod = order.payment_method || '-';
                    
                    html += `
                    <tr>
                        <td><strong>#${order.id}</strong></td>
                        <td>${order.nama}</td>
                        <td class="text-success fw-bold">Rp ${formatRupiah(order.total_harga)}</td>
                        <td><span class="badge ${badge}">${order.status.toUpperCase()}</span></td>
                        <td>${paymentMethod}</td>
                        <td>${formatDate(order.tanggal_order)}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewDetail(${order.id})">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="changeStatus(${order.id}, '${order.status}')">
                                <i class="bi bi-arrow-repeat"></i> Status
                            </button>
                        </td>
                    </tr>`;
                });
            }

            document.getElementById("ordersTableBody").innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            showAlert('danger', 'Failed to load orders');
        });
}

// VIEW ORDER DETAIL
function viewDetail(orderId) {
    document.getElementById('orderDetailContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-warning" role="status"></div>
        </div>`;
    
    detailModal.show();
    
    fetch(`../api/api.php?action=get_order_detail&order_id=${orderId}`)
        .then(res => res.json())
        .then(data => {
            let html = `
                <div class="mb-3">
                    <h6 class="fw-bold">Order Information:</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Order ID:</strong></td><td>#${data.order.id}</td></tr>
                        <tr><td><strong>Customer:</strong></td><td>${data.order.nama}</td></tr>
                        <tr><td><strong>Email:</strong></td><td>${data.order.email}</td></tr>
                        <tr><td><strong>Total:</strong></td><td class="text-success fw-bold">Rp ${formatRupiah(data.order.total_harga)}</td></tr>
                        <tr><td><strong>Status:</strong></td><td><span class="badge ${data.order.status === 'paid' ? 'bg-success' : 'bg-warning text-dark'}">${data.order.status.toUpperCase()}</span></td></tr>
                        <tr><td><strong>Date:</strong></td><td>${formatDate(data.order.tanggal_order)}</td></tr>
                    </table>
                </div>
                
                <h6 class="fw-bold mb-3">Items Purchased:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Game</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            data.items.forEach(item => {
                html += `
                    <tr>
                        <td>${item.nama_game}</td>
                        <td>Rp ${formatRupiah(item.harga)}</td>
                        <td>${item.qty}</td>
                        <td>Rp ${formatRupiah(item.harga * item.qty)}</td>
                    </tr>`;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>`;
            
            document.getElementById('orderDetailContent').innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            document.getElementById('orderDetailContent').innerHTML = 
                `<div class="alert alert-danger">Failed to load order details</div>`;
        });
}

// CHANGE STATUS
function changeStatus(orderId, currentStatus) {
    currentOrderId = orderId;
    document.getElementById('orderIdStatus').textContent = orderId;
    document.getElementById('newStatus').value = currentStatus;
    statusModal.show();
}

function confirmChangeStatus() {
    const newStatus = document.getElementById('newStatus').value;
    
    let formData = new FormData();
    formData.append('order_id', currentOrderId);
    formData.append('status', newStatus);
    
    fetch("../api/api.php?action=update_order_status", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(r => {
        statusModal.hide();
        
        if (r.status) {
            showAlert('success', '✓ Order status updated!');
            loadOrders();
        } else {
            showAlert('danger', '✗ Failed to update status: ' + r.message);
        }
    })
    .catch(err => {
        console.error(err);
        showAlert('danger', '✗ Error occurred');
    });
}

// HELPER FUNCTIONS
function showAlert(type, message) {
    const alertBox = document.getElementById('alertBox');
    alertBox.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
    
    setTimeout(() => {
        alertBox.innerHTML = '';
    }, 4000);
}

function formatRupiah(angka) {
    return parseInt(angka).toLocaleString('id-ID');
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>

</body>
</html>