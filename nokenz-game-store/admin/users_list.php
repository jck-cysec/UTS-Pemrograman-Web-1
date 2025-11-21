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
    <title>Manage Users - Admin</title>
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
                    <a class="nav-link text-white active" href="users_list.php">
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
                <i class="bi bi-people"></i> Manage Users
            </h2>

            <!-- Alert Box -->
            <div id="alertBox"></div>

            <!-- USERS TABLE -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Registered Users</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
                            
                            while ($user = mysqli_fetch_assoc($query)):
                                $badge = $user['role'] == 'admin' ? 'bg-danger' : 'bg-success';
                                $canDelete = $user['role'] != 'admin' && $user['id'] != $_SESSION['user_id'];
                            ?>
                            <tr>
                                <td><strong>#<?= $user['id'] ?></strong></td>
                                <td><strong><?= htmlspecialchars($user['nama']) ?></strong></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><span class="badge <?= $badge ?>"><?= strtoupper($user['role']) ?></span></td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <?php if ($canDelete): ?>
                                        <button class="btn btn-sm btn-danger" 
                                                onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['nama']) ?>')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Protected</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle"></i> Confirm Delete User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
                <p class="fw-bold text-danger" id="userNameToDelete"></p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i> 
                    This action cannot be undone. All user's orders and cart will also be deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
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
let deleteUserId = 0;
let deleteModal;

document.addEventListener('DOMContentLoaded', function() {
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
});

function deleteUser(id, name) {
    deleteUserId = id;
    document.getElementById('userNameToDelete').textContent = name;
    deleteModal.show();
}

function confirmDelete() {
    let formData = new FormData();
    formData.append('id', deleteUserId);

    fetch("../api/api.php?action=delete_user", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(r => {
        deleteModal.hide();
        
        if (r.status) {
            showAlert('success', '✓ User deleted successfully!');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('danger', '✗ Failed to delete user: ' + r.message);
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
</script>

</body>
</html>