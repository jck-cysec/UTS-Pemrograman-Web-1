<?php
include "../config/auth_user.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History Pembelian - Nokenz Game Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
    <style>
        .invoice-modal .modal-dialog {
            max-width: 800px;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
        }
        
        .invoice-body {
            padding: 2rem;
        }
        
        .invoice-table {
            margin-top: 1.5rem;
        }
        
        .invoice-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #667eea;
        }
        
        .invoice-footer {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0 0 15px 15px;
        }
        
        .invoice-total {
            font-size: 1.5rem;
            color: #667eea;
            font-weight: bold;
        }
        
        .invoice-info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice-modal, .invoice-modal * {
                visibility: visible;
            }
            .invoice-modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .modal-header .btn-close,
            .btn-print,
            .invoice-footer .btn {
                display: none !important;
            }
        }
    </style>
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
    <h3 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> Riwayat Pembelian</h3>
    <div id="alertBox"></div>
    <div id="historyList"></div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
                <p class="fw-bold">Order #<span id="orderIdToDelete"></span></p>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle"></i> This action cannot be undone!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div class="modal fade invoice-modal" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header invoice-header">
                <div class="w-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-0"><i class="bi bi-receipt"></i> INVOICE</h3>
                            <p class="mb-0 mt-2 opacity-75">Nokenz Game Store</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="mt-3">
                        <h5 class="mb-0">Order #<span id="invoiceOrderId"></span></h5>
                        <small class="opacity-75"><i class="bi bi-calendar"></i> <span id="invoiceDate"></span></small>
                    </div>
                </div>
            </div>
            <div class="modal-body invoice-body">
                <!-- Customer Info -->
                <div class="invoice-info-box">
                    <h6 class="fw-bold mb-2"><i class="bi bi-person-circle"></i> Customer Information</h6>
                    <p class="mb-1"><strong>Name:</strong> <span id="invoiceCustomerName"></span></p>
                    <p class="mb-0"><strong>Email:</strong> <span id="invoiceCustomerEmail"></span></p>
                </div>

                <!-- Payment Status -->
                <div class="invoice-info-box">
                    <h6 class="fw-bold mb-2"><i class="bi bi-credit-card"></i> Payment Status</h6>
                    <p class="mb-0"><span id="invoiceStatus"></span></p>
                </div>

                <!-- Items Table -->
                <h6 class="fw-bold mt-4 mb-3"><i class="bi bi-box-seam"></i> Order Items</h6>
                <table class="table table-bordered invoice-table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Game</th>
                            <th width="150" class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceItems">
                        <!-- Items will be loaded here -->
                    </tbody>
                </table>

                <!-- Total -->
                <div class="text-end mt-4">
                    <h5 class="mb-2">Total Amount:</h5>
                    <div class="invoice-total">Rp <span id="invoiceTotal"></span></div>
                </div>
            </div>
            <div class="modal-footer invoice-footer">
                <div class="w-100 text-center">
                    <button type="button" class="btn btn-primary me-2 btn-print" onclick="printInvoice()">
                        <i class="bi bi-printer"></i> Print Invoice
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <hr class="my-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> Thank you for shopping at Nokenz Game Store!<br>
                        © 2025 23552011072_HaidirZacky_CNS B
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4">
    <p class="mb-1">© 2025 23552011072_HaidirZacky_CNS B</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let deleteOrderId = 0;
let deleteModal;
let invoiceModal;
let currentInvoiceData = {};

document.addEventListener('DOMContentLoaded', function() {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    loadHistory();
});

function loadHistory() {
    fetch("../api/api.php?action=order_history")
        .then(res => res.json())
        .then(data => {
            let html = "";
            if (data.length === 0) {
                document.getElementById("historyList").innerHTML =
                    `<p class="text-muted">Belum ada history pembelian. Silahkan beli dulu ya gamenya!!</p>`;
                return;
            }
            data.forEach(order => {
                const statusBadge = order.status === 'paid' 
                    ? '<span class="badge badge-paid"><i class="bi bi-check-circle"></i> Paid</span>' 
                    : '<span class="badge badge-pending"><i class="bi bi-clock"></i> Pending</span>';
                html += `
                <div class="history-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="bi bi-receipt"></i> Order #${order.id}</h5>
                            <small class="text-muted"><i class="bi bi-calendar"></i> ${order.tanggal_order}</small>
                        </div>
                        <div>
                            ${statusBadge}
                            <button class="btn btn-sm btn-info ms-2" onclick="showInvoice(${order.id})">
                                <i class="bi bi-file-earmark-text"></i> Invoice
                            </button>
                            <button class="btn btn-sm btn-danger ms-2" onclick="deleteOrder(${order.id})">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div id="items-${order.id}" class="mt-3">
                        <div class="text-muted">Loading items...</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total:</strong>
                        <strong class="text-success">Rp ${formatRupiah(order.total_harga)}</strong>
                    </div>
                </div>`;
                loadOrderItems(order.id);
            });
            document.getElementById("historyList").innerHTML = html;
        });
}

function loadOrderItems(orderId) {
    fetch(`../api/api.php?action=order_items&order_id=${orderId}`)
        .then(res => res.json())
        .then(items => {
            let itemsHtml = "";
            items.forEach(item => {
                itemsHtml += `
                <div class="d-flex align-items-center mb-2">
                    <img src="../assets/images/games/${item.gambar}" 
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" 
                         class="me-3">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${item.nama_game}</div>
                    </div>
                    <div class="text-success fw-bold">Rp ${formatRupiah(item.harga)}</div>
                </div>`;
            });
            document.getElementById(`items-${orderId}`).innerHTML = itemsHtml;
        });
}

function showInvoice(orderId) {
    // Get order details
    fetch("../api/api.php?action=order_history")
        .then(res => res.json())
        .then(orders => {
            const order = orders.find(o => o.id == orderId);
            if (!order) return;
            
            currentInvoiceData = order;
            
            // Set invoice header
            document.getElementById('invoiceOrderId').textContent = order.id;
            document.getElementById('invoiceDate').textContent = order.tanggal_order;
            document.getElementById('invoiceTotal').textContent = formatRupiah(order.total_harga);
            
            // Set customer info (dari session PHP)
            document.getElementById('invoiceCustomerName').textContent = '<?= $_SESSION['nama'] ?? 'Customer' ?>';
            document.getElementById('invoiceCustomerEmail').textContent = '<?= $_SESSION['email'] ?? '-' ?>';
            
            // Set status badge
            const statusHtml = order.status === 'paid' 
                ? '<span class="badge badge-paid"><i class="bi bi-check-circle"></i> PAID</span>' 
                : '<span class="badge badge-pending"><i class="bi bi-clock"></i> PENDING</span>';
            document.getElementById('invoiceStatus').innerHTML = statusHtml;
            
            // Load items
            fetch(`../api/api.php?action=order_items&order_id=${orderId}`)
                .then(res => res.json())
                .then(items => {
                    let itemsHtml = "";
                    items.forEach((item, index) => {
                        itemsHtml += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="../assets/images/games/${item.gambar}" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;" 
                                         class="me-2">
                                    <strong>${item.nama_game}</strong>
                                </div>
                            </td>
                            <td class="text-end">Rp ${formatRupiah(item.harga)}</td>
                        </tr>`;
                    });
                    document.getElementById('invoiceItems').innerHTML = itemsHtml;
                    
                    // Show modal
                    invoiceModal.show();
                });
        });
}

function printInvoice() {
    window.print();
}

function deleteOrder(orderId) {
    deleteOrderId = orderId;
    document.getElementById('orderIdToDelete').textContent = orderId;
    deleteModal.show();
}

function confirmDelete() {
    let formData = new FormData();
    formData.append('order_id', deleteOrderId);
    fetch("../api/api.php?action=delete_order", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(r => {
        deleteModal.hide();
        if (r.status) {
            showAlert('success', '✓ Order deleted successfully!');
            loadHistory();
        } else {
            showAlert('danger', '✗ Failed to delete order: ' + r.message);
        }
    })
    .catch(err => {
        console.error(err);
        showAlert('danger', '✗ Error occurred');
    });
}

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
</script>
</body>
</html>