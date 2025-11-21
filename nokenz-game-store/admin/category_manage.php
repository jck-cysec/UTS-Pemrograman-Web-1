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
    <title>Manage Categories - Admin</title>
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
            <span class="text-white me-3">Hi, <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></strong></span>
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
                    <a class="nav-link text-white active" href="category_manage.php">
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
                <i class="bi bi-tags-fill"></i> Manage Game Categories
            </h2>

            <div id="alertBox"></div>

            <div class="row">
                
                <!-- GENRE SECTION -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-joystick"></i> Game Genres
                            </h5>
                        </div>
                        <div class="card-body">
                            
                            <form id="addGenreForm" class="mb-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="genre" placeholder="Add new genre..." required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-plus-circle"></i> Add
                                    </button>
                                </div>
                            </form>

                            <div id="genreList" class="pt-2">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary"></div>
                                    <span class="ms-2">Loading...</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- PLATFORM SECTION -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-laptop"></i> Game Platforms
                            </h5>
                        </div>
                        <div class="card-body">
                            
                            <form id="addPlatformForm" class="mb-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="platform" placeholder="Add new platform..." required>
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-plus-circle"></i> Add
                                    </button>
                                </div>
                            </form>

                            <div id="platformList" class="pt-2">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-success"></div>
                                    <span class="ms-2">Loading...</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- EDIT GENRE MODAL -->
<div class="modal fade" id="editGenreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Edit Genre
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="oldGenre">
                <label class="mb-2">Genre Name:</label>
                <input type="text" id="newGenre" class="form-control mb-3" required>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> This will update all games with this genre
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="updateGenre()">
                    <i class="bi bi-check-circle"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT PLATFORM MODAL -->
<div class="modal fade" id="editPlatformModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Edit Platform
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="oldPlatform">
                <label class="mb-2">Platform Name:</label>
                <input type="text" id="newPlatform" class="form-control mb-3" required>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> This will update all games with this platform
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success" onclick="updatePlatform()">
                    <i class="bi bi-check-circle"></i> Save Changes
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
let genreModal, platformModal;

document.addEventListener("DOMContentLoaded", () => {
    genreModal = new bootstrap.Modal(document.getElementById("editGenreModal"));
    platformModal = new bootstrap.Modal(document.getElementById("editPlatformModal"));
    loadCategories();
});

function esc(str) {
    return str.replace(/&/g,"&amp;")
              .replace(/</g,"&lt;")
              .replace(/>/g,"&gt;")
              .replace(/"/g,"&quot;")
              .replace(/'/g,"&#039;");
}

// LOAD
function loadCategories() {
    loadGenreList();
    loadPlatformList();
}

function loadGenreList() {
    console.log('Loading genres...'); 
    
    fetch("../api/api.php?action=get_genres")
    .then(r => {
        console.log('Response status:', r.status);
        if (!r.ok) throw new Error('HTTP error ' + r.status);
        return r.json();
    })
    .then(list => {
        console.log('Genres received:', list);
        
        let html = "";
        if (!Array.isArray(list) || list.length === 0) {
            html = `<p class="text-muted">No genres found. Add games first!</p>`;
        } else {
            list.forEach(name => {
                const safeName = esc(String(name));
                html += `
                <span class="badge bg-primary category-badge">
                    ${safeName}
                    <span class="category-tools">
                        <button class="btn btn-sm btn-link text-white p-0 ms-2" onclick="openEditGenre('${safeName}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-white p-0 ms-1" onclick="deleteGenre('${safeName}')">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </span>
                </span>`;
            });
        }
        document.getElementById("genreList").innerHTML = html;
    })
    .catch(err => {
        console.error('Error loading genres:', err);
        document.getElementById("genreList").innerHTML = 
            `<div class="alert alert-danger">Error: ${err.message}</div>`;
    });
}

function loadPlatformList() {
    console.log('Loading platforms...');
    
    fetch("../api/api.php?action=get_platforms")
    .then(r => {
        console.log('Response status:', r.status);
        if (!r.ok) throw new Error('HTTP error ' + r.status);
        return r.json();
    })
    .then(list => {
        console.log('Platforms received:', list);
        
        let html = "";
        if (!Array.isArray(list) || list.length === 0) {
            html = `<p class="text-muted">No platforms found. Add games first!</p>`;
        } else {
            list.forEach(name => {
                const safeName = esc(String(name));
                html += `
                <span class="badge bg-success category-badge">
                    ${safeName}
                    <span class="category-tools">
                        <button class="btn btn-sm btn-link text-white p-0 ms-2" onclick="openEditPlatform('${safeName}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-white p-0 ms-1" onclick="deletePlatform('${safeName}')">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </span>
                </span>`;
            });
        }
        document.getElementById("platformList").innerHTML = html;
    })
    .catch(err => {
        console.error('Error loading platforms:', err);
        document.getElementById("platformList").innerHTML = 
            `<div class="alert alert-danger">Error: ${err.message}</div>`;
    });
}

// ADD GENRE
document.getElementById("addGenreForm").addEventListener("submit", e => {
    e.preventDefault();
    let fd = new FormData(e.target);

    fetch("../api/api.php?action=add_genre", { method:"POST", body: fd })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            showAlert("success", "✓ Genre added!");
            e.target.reset();
            loadGenreList();
        } else showAlert("danger", res.message);
    });
});

// ADD PLATFORM
document.getElementById("addPlatformForm").addEventListener("submit", e => {
    e.preventDefault();
    let fd = new FormData(e.target);

    fetch("../api/api.php?action=add_platform", { method:"POST", body: fd })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            showAlert("success", "✓ Platform added!");
            e.target.reset();
            loadPlatformList();
        } else showAlert("danger", res.message);
    });
});

// EDIT GENRE
function openEditGenre(name) {
    document.getElementById("oldGenre").value = name;
    document.getElementById("newGenre").value = name;
    genreModal.show();
}

function updateGenre() {
    let fd = new FormData();
    fd.append("old_genre", document.getElementById("oldGenre").value);
    fd.append("new_genre", document.getElementById("newGenre").value);

    fetch("../api/api.php?action=update_genre", { method:"POST", body: fd })
    .then(r => r.json())
    .then(res => {
        genreModal.hide();
        if (res.status) {
            showAlert("success", "✓ Genre updated!");
            loadGenreList();
        } else showAlert("danger", res.message);
    });
}

// EDIT PLATFORM
function openEditPlatform(name) {
    document.getElementById("oldPlatform").value = name;
    document.getElementById("newPlatform").value = name;
    platformModal.show();
}

function updatePlatform() {
    let fd = new FormData();
    fd.append("old_platform", document.getElementById("oldPlatform").value);
    fd.append("new_platform", document.getElementById("newPlatform").value);

    fetch("../api/api.php?action=update_platform", { method:"POST", body: fd })
    .then(r => r.json())
    .then(res => {
        platformModal.hide();
        if (res.status) {
            showAlert("success", "✓ Platform updated!");
            loadPlatformList();
        } else showAlert("danger", res.message);
    });
}

// DELETE GENRE
function deleteGenre(name) {
    if (!confirm(`Delete genre "${name}"?\nGames will NOT be deleted.`)) return;

    let fd = new FormData();
    fd.append("genre", name);

    fetch("../api/api.php?action=delete_genre", { method:"POST", body: fd })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            showAlert("success", "✓ Genre removed!");
            loadGenreList();
        } else showAlert("danger", res.message);
    });
}

// DELETE PLATFORM
function deletePlatform(name) {
    if (!confirm(`Delete platform "${name}"?`)) return;

    let fd = new FormData();
    fd.append("platform", name);

    fetch("../api/api.php?action=delete_platform", { method:"POST", body: fd })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            showAlert("success", "✓ Platform removed!");
            loadPlatformList();
        } else showAlert("danger", res.message);
    });
}

function showAlert(type, msg) {
    document.getElementById("alertBox").innerHTML = `
        <div class="alert alert-${type} fade show alert-dismissible">
            ${msg}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
}
</script>

</body>
</html>