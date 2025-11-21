<?php
include "../config/auth_user.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Nokenz Game Store</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/user.css">
    
    <style>
        .text-purple { color: #764ba2; }
    </style>
</head>

<body class="bg-light">

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="text-center text-white">
        <div class="spinner mx-auto mb-3"></div>
        <h5><i class="bi bi-hourglass-split me-2"></i>Memproses pembayaran...</h5>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">
            <i class="bi bi-controller me-2"></i>Nokenz Game Store
        </a>
        <div class="d-flex align-items-center">
            <a href="cart.php" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <a href="logout.php" class="btn btn-outline-light">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container mt-4 mb-5">
    
    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle p-3 me-3" style="background: var(--accent-gradient);">
            <i class="bi bi-cart-check-fill fs-3 text-dark"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-1">Checkout</h2>
            <p class="text-muted mb-0">Review your order and complete payment</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alertBox"></div>

    <div class="row">
        
        <!-- Left Column: Items List -->
        <div class="col-lg-7 mb-4">
            <div class="bg-white p-4 rounded-3 shadow-sm border-0">
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-bag-check fs-4 text-primary me-2"></i>
                    <h5 class="fw-bold mb-0">Items in Your Order</h5>
                </div>
                
                <div id="checkoutList">
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-3">Loading your items...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Payment Summary -->
        <div class="col-lg-5">
            <div class="summary-card sticky-top" style="top: 20px;">
                
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-credit-card fs-4 text-success me-2"></i>
                    <h5 class="fw-bold mb-0">Payment Summary</h5>
                </div>
                
                <!-- Price Breakdown -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-semibold" id="subtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Admin Fee:</span>
                        <span class="text-success fw-semibold">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Total Payment:</h5>
                        <h3 class="text-success fw-bold mb-0" id="totalHarga">Rp 0</h3>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-wallet2 fs-5 me-2"></i>
                        <h6 class="fw-bold mb-0">Select Payment Method:</h6>
                    </div>
                    
                    <label class="payment-method" for="bank_transfer">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" id="bank_transfer" value="bank_transfer" checked>
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-bold">
                                    <i class="bi bi-bank text-primary"></i> Bank Transfer
                                </div>
                                <small class="text-muted">BCA, BNI, Mandiri, BRI</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </label>

                    <label class="payment-method" for="ovo">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" id="ovo" value="ovo">
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-bold">
                                    <i class="bi bi-phone text-purple"></i> OVO
                                </div>
                                <small class="text-muted">E-wallet payment</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </label>

                    <label class="payment-method" for="gopay">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="paymentMethod" id="gopay" value="gopay">
                            <div class="ms-3 flex-grow-1">
                                <div class="fw-bold">
                                    <i class="bi bi-wallet-fill text-success"></i> GOPAY
                                </div>
                                <small class="text-muted">E-wallet payment</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </label>
                </div>

                <!-- Checkout Button -->
                <button class="btn btn-warning btn-lg w-100 mb-3" onclick="confirmCheckout()" id="btnCheckout">
                    <i class="bi bi-check-circle me-2"></i>Complete Payment
                </button>

                <!-- Terms -->
                <div class="text-center">
                    <small class="text-muted d-flex align-items-center justify-content-center">
                        <i class="bi bi-shield-check me-1"></i>
                        Secure payment protected
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">You are about to complete payment using:</p>
                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong id="selectedMethod"></strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded">
                    <span class="text-muted">Total Amount:</span>
                    <strong class="text-success fs-5" id="modalTotal"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="processCheckout()">
                    <i class="bi bi-check2-circle me-1"></i>Confirm & Pay
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let cartData = [];
let totalAmount = 0;
let confirmModal;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    loadCheckoutData();
    
    // Add event listeners for payment method cards
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
    
    // Set active on initial load
    document.querySelector('.payment-method').classList.add('active');
});

// Load cart data for checkout
function loadCheckoutData() {
    fetch("../api/api.php?action=get_cart")
        .then(res => res.json())
        .then(data => {
            cartData = data;
            let html = "";
            let total = 0;

            if (data.length === 0) {
                document.getElementById("checkoutList").innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Your cart is empty</h5>
                        <p class="text-muted mb-3">Add some games to your cart first</p>
                        <a href="home.php" class="btn btn-primary">
                            <i class="bi bi-shop me-2"></i>Browse Games
                        </a>
                    </div>`;
                document.getElementById("btnCheckout").disabled = true;
                return;
            }

            data.forEach(item => {
                total += Number(item.harga);
                html += `
                    <div class="item-card">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <img src="../assets/images/games/${item.ggambar}" 
                                     style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px;"
                                     class="me-3">
                                <span class="position-absolute top-0 start-0 badge bg-success rounded-pill" style="font-size: 0.65rem;">
                                    <i class="bi bi-check"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1">${item.nama_game}</div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-secondary">${item.genre}</span>
                                    <span class="badge bg-info">${item.platform}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-success fw-bold fs-5">Rp ${formatRupiah(item.harga)}</div>
                                <small class="text-muted">Digital Copy</small>
                            </div>
                        </div>
                    </div>`;
            });

            totalAmount = total;
            document.getElementById("checkoutList").innerHTML = html;
            document.getElementById("subtotal").innerText = "Rp " + formatRupiah(total);
            document.getElementById("totalHarga").innerText = "Rp " + formatRupiah(total);
        })
        .catch(err => {
            showAlert('danger', '<i class="bi bi-x-circle me-2"></i>Failed to load data. Please refresh the page.');
            console.error(err);
        });
}

// Show confirmation modal
function confirmCheckout() {
    if (cartData.length === 0) {
        showAlert('warning', '<i class="bi bi-exclamation-triangle me-2"></i>Your cart is empty!');
        return;
    }

    const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked');
    const methodText = selectedMethod.parentElement.querySelector('.fw-bold').innerHTML;
    
    document.getElementById('selectedMethod').innerHTML = methodText;
    document.getElementById('modalTotal').textContent = "Rp " + formatRupiah(totalAmount);
    
    confirmModal.show();
}

// Process checkout
function processCheckout() {
    confirmModal.hide();
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.add('show');
    document.getElementById('btnCheckout').disabled = true;

    // Step 1: Create order
    fetch("../api/api.php?action=checkout", { method: "POST" })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(r => {
            console.log('Checkout response:', r);
            
            if (r.status) {
                const orderId = r.order_id;
                const method = document.querySelector('input[name="paymentMethod"]:checked').value;

                // Step 2: Confirm payment
                fetch("../api/api.php?action=payment_confirm", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `order_id=${orderId}&method=${method}`
                })
                .then(res => {
                    if (!res.ok) throw new Error('Payment confirmation failed');
                    return res.json();
                })
                .then(resp => {
                    console.log('Payment response:', resp);
                    
                    document.getElementById('loadingOverlay').classList.remove('show');
                    
                    if (resp.status) {
                        showAlert('success', '<i class="bi bi-check-circle me-2"></i>Payment successful! Thank you for your purchase.');
                        
                        setTimeout(() => {
                            window.location.href = "order_history.php";
                        }, 2000);
                    } else {
                        showAlert('danger', '<i class="bi bi-x-circle me-2"></i>Payment failed: ' + (resp.message || 'Unknown error'));
                        document.getElementById('btnCheckout').disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Payment error:', err);
                    document.getElementById('loadingOverlay').classList.remove('show');
                    showAlert('danger', '<i class="bi bi-exclamation-triangle me-2"></i>Payment confirmation error: ' + err.message);
                    document.getElementById('btnCheckout').disabled = false;
                });

            } else {
                document.getElementById('loadingOverlay').classList.remove('show');
                showAlert('danger', '<i class="bi bi-x-circle me-2"></i>Checkout failed: ' + (r.message || 'Unknown error'));
                document.getElementById('btnCheckout').disabled = false;
            }
        })
        .catch(err => {
            console.error('Checkout error:', err);
            document.getElementById('loadingOverlay').classList.remove('show');
            showAlert('danger', '<i class="bi bi-wifi-off me-2"></i>Connection error: ' + err.message);
            document.getElementById('btnCheckout').disabled = false;
        });
}

// Show alert message
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

// Format number to Rupiah
function formatRupiah(angka) {
    return parseInt(angka).toLocaleString('id-ID');
}
</script>

</body>
</html>