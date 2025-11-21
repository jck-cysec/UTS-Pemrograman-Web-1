<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nokenz Game Store - Your Gaming Paradise</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-controller"></i> Nokenz Game Store
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#games">
                        <i class="bi bi-collection"></i> Games
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#news">
                        <i class="bi bi-newspaper"></i> News
                    </a>
                </li>
                <li class="nav-item ms-3">
                    <a href="user/login.php" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a href="user/register.php" class="btn btn-gradient rounded-pill px-4">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="display-3 fw-bold mb-4">
                <i class="bi bi-joystick"></i> Welcome to Nokenz Game Store
            </h1>
            <p class="lead fs-4 mb-5">Tempat terbaik untuk membeli game original dengan harga terjangkau</p>
            <a href="#games" class="btn btn-gradient btn-lg">
                <i class="bi bi-search"></i> Explore Games
            </a>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">
                <i class="bi bi-star-fill text-warning"></i> Kenapa Memilih Kami?
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Aman & Terpercaya</h5>
                    <p class="text-muted mb-0">Transaksi aman dan terjamin</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Game Original</h5>
                    <p class="text-muted mb-0">Semua game bergaransi resmi</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Harga Terjangkau</h5>
                    <p class="text-muted mb-0">Harga kompetitif setiap hari</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-headset"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Support 24/7</h5>
                    <p class="text-muted mb-0">Kami selalu siap membantu</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- GAMES SECTION -->
<section class="py-5 bg-dark text-white" id="games">
    <div class="container">
        
        <div class="text-center mb-4">
            <h2 class="section-title text-white">
                <i class="bi bi-fire text-warning"></i> Game Populer
            </h2>
        </div>

        <!-- FILTER -->
        <div class="filter-section">
            <div class="row g-3 align-items-end">
                
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-search"></i> Search Game
                    </label>
                    <input type="text" class="form-control" id="searchInput" 
                           placeholder="Cari nama game..." onkeyup="applyFilters()">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-collection"></i> Genre
                    </label>
                    <select class="form-select" id="genreFilter" onchange="applyFilters()">
                        <option value="">All Genres</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-laptop"></i> Platform
                    </label>
                    <select class="form-select" id="platformFilter" onchange="applyFilters()">
                        <option value="">All Platforms</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold">
                        <i class="bi bi-sort-down"></i> Sort
                    </label>
                    <select class="form-select" id="sortSelect" onchange="applyFilters()">
                        <option value="newest">Newest</option>
                        <option value="cheapest">Cheapest</option>
                        <option value="expensive">Most Expensive</option>
                        <option value="a-z">A - Z</option>
                        <option value="z-a">Z - A</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <button class="btn btn-warning w-100" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>

            </div>

            <div id="activeFilters" class="mt-3"></div>
        </div>

        <!-- GAME COUNT -->
        <div class="text-center mb-4">
            <span class="badge bg-warning text-dark px-4 py-2 fs-6" id="gameCount">
                <i class="bi bi-controller"></i> Loading...
            </span>
        </div>

        <!-- GAMES GRID -->
        <div class="row g-4" id="gameList">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-warning" role="status"></div>
                <p class="mt-3">Loading games...</p>
            </div>
        </div>

    </div>
</section>

<!-- NEWS SECTION -->
<section class="py-5 bg-light" id="news">
    <div class="container">
        
        <div class="text-center mb-5">
            <h2 class="section-title">
                <i class="bi bi-newspaper text-primary"></i> Berita Terbaru
            </h2>
        </div>

        <div class="row g-4" id="newsList"></div>
        
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-2">
                    <i class="bi bi-controller"></i> Nokenz Game Store
                </h5>
                <p class="mb-0" style="opacity: 0.8;">Tempat terbaik untuk membeli game original dengan harga terjangkau</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <p class="mb-0">© 2025 23552011072_HaidirZacky_CNS B</p>
            </div>
        </div>
    </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let allGames = [];
let filteredGames = [];

let allNews = [];
let currentNewsIndex = 0;
let newsAutoInterval;
let totalNewsGroups = 0;

document.addEventListener("DOMContentLoaded", () => {
    loadGames();
    loadNews();
});

function loadGames() {
    fetch("api/api.php?action=get_games")
        .then(res => res.json())
        .then(data => {
            allGames = data;
            filteredGames = [...data];
            loadGenres();
            loadPlatforms();
            applyFilters();
        });
}

function loadGenres() {
    fetch("api/api.php?action=get_genres")
        .then(res => res.json())
        .then(list => {
            const sel = document.getElementById("genreFilter");
            list.forEach(g => {
                let op = document.createElement("option");
                op.value = g;
                op.textContent = g;
                sel.appendChild(op);
            });
        });
}

function loadPlatforms() {
    fetch("api/api.php?action=get_platforms")
        .then(res => res.json())
        .then(list => {
            const sel = document.getElementById("platformFilter");
            list.forEach(p => {
                let op = document.createElement("option");
                op.value = p;
                op.textContent = p;
                sel.appendChild(op);
            });
        });
}

function applyFilters() {
    const search = document.getElementById("searchInput").value.toLowerCase();
    const genre = document.getElementById("genreFilter").value;
    const platform = document.getElementById("platformFilter").value;
    const sort = document.getElementById("sortSelect").value;

    filteredGames = allGames.filter(g => {
        const s1 = g.nama_game.toLowerCase().includes(search);
        const s2 = !genre || g.genre === genre;
        const s3 = !platform || g.platform === platform;
        return s1 && s2 && s3;
    });

    sortGames(sort);
    displayGames();
    updateActiveFilters(search, genre, platform, sort);
}

function sortGames(type) {
    switch(type) {
        case "newest": filteredGames.sort((a,b) => b.id - a.id); break;
        case "cheapest": filteredGames.sort((a,b) => a.harga - b.harga); break;
        case "expensive": filteredGames.sort((a,b) => b.harga - a.harga); break;
        case "a-z": filteredGames.sort((a,b) => a.nama_game.localeCompare(b.nama_game)); break;
        case "z-a": filteredGames.sort((a,b) => b.nama_game.localeCompare(a.nama_game)); break;
    }
}

function displayGames() {
    const c = document.getElementById("gameList");
    const badge = document.getElementById("gameCount");

    if (filteredGames.length === 0) {
        c.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-warning"></i>
                <h4 class="mt-3">No games found</h4>
                <p class="text-muted">Try adjusting your filters</p>
            </div>`;
        badge.innerHTML = '<i class="bi bi-controller"></i> 0 games';
        return;
    }

    let html = "";
    filteredGames.forEach(g => {
        const img = g.gambar ? `assets/images/games/${g.gambar}` : `assets/images/noimage.png`;

        html += `
        <div class="col-lg-4 col-md-6">
            <div class="game-card">
                <img src="${img}" class="img-fluid" alt="${g.nama_game}">
                <div class="game-card-body text-white">
                    <h5 class="fw-bold mb-3">${g.nama_game}</h5>
                    <div class="mb-3">
                        <span class="badge game-badge bg-primary">
                            <i class="bi bi-collection"></i> ${g.genre}
                        </span>
                        <span class="badge game-badge bg-info">
                            <i class="bi bi-laptop"></i> ${g.platform}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <h5 class="text-warning fw-bold mb-0">
                            <i class="bi bi-tag-fill"></i> Rp ${formatRupiah(g.harga)}
                        </h5>
                        <a href="user/detail.php?id=${g.id}" class="btn btn-warning btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>`;
    });

    c.innerHTML = html;
    badge.innerHTML = `<i class="bi bi-controller"></i> ${filteredGames.length} games`;
}

function updateActiveFilters(search, genre, platform, sort) {
    const div = document.getElementById("activeFilters");
    let tags = "";

    if (search) tags += `<span class="filter-badge" onclick="clearSearch()"><i class="bi bi-search"></i> "${search}" <i class="bi bi-x-lg"></i></span>`;
    if (genre) tags += `<span class="filter-badge" onclick="clearGenre()"><i class="bi bi-collection"></i> ${genre} <i class="bi bi-x-lg"></i></span>`;
    if (platform) tags += `<span class="filter-badge" onclick="clearPlatform()"><i class="bi bi-laptop"></i> ${platform} <i class="bi bi-x-lg"></i></span>`;

    const sortNames = {
        "cheapest": "Cheapest First",
        "expensive": "Most Expensive",
        "a-z": "A → Z",
        "z-a": "Z → A"
    };

    if (sort !== "newest") tags += `<span class="filter-badge"><i class="bi bi-sort-down"></i> ${sortNames[sort]}</span>`;

    div.innerHTML = tags ? '<strong>Active Filters:</strong> ' + tags : '';
}

function clearSearch(){ document.getElementById("searchInput").value=""; applyFilters(); }
function clearGenre(){ document.getElementById("genreFilter").value=""; applyFilters(); }
function clearPlatform(){ document.getElementById("platformFilter").value=""; applyFilters(); }

function resetFilters() {
    document.getElementById("searchInput").value="";
    document.getElementById("genreFilter").value="";
    document.getElementById("platformFilter").value="";
    document.getElementById("sortSelect").value="newest";
    applyFilters();
}

function formatRupiah(n){ return parseInt(n).toLocaleString("id-ID"); }

function loadNews() {
    fetch("api/api.php?action=get_news")
        .then(r => r.json())
        .then(list => {
            let html = "";
            list.slice(0, 3).forEach(n => {
                const img = n.gambar ? `assets/images/news/${n.gambar}` : `assets/images/noimage.png`;

                html += `
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <img src="${img}" class="img-fluid" alt="${n.judul}">
                        <div class="p-4">
                            <h5 class="fw-bold mb-3">${n.judul}</h5>
                            <p class="text-muted">${n.deskripsi.substring(0,100)}...</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> ${n.tanggal}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>`;
            });

            document.getElementById("newsList").innerHTML = html;
        });
}
</script>

</body>
</html>