// ===========================
// USER HOME - Main JavaScript
// ===========================

let allGames = [];
let filteredGames = [];

// Load everything on page load
window.addEventListener('DOMContentLoaded', function() {
    loadGames();
    loadGenres();
    loadPlatforms();
});

// Load games
function loadGames() {
    fetch("../api/api.php?action=get_games")
        .then(res => res.json())
        .then(data => {
            allGames = data;
            filteredGames = [...data];
            applyFilters();
        })
        .catch(err => {
            console.error(err);
            document.getElementById("gameContainer").innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        ✗ Failed to load games.
                    </div>
                </div>`;
        });
}

// Load genres from API
function loadGenres() {
    fetch("../api/api.php?action=get_genres")
        .then(r => r.json())
        .then(list => {
            const genreSelect = document.getElementById("genreFilter");
            list.forEach(g => {
                let op = document.createElement("option");
                op.value = g;
                op.textContent = g;
                genreSelect.appendChild(op);
            });
        });
}

// Load platforms from API
function loadPlatforms() {
    fetch("../api/api.php?action=get_platforms")
        .then(r => r.json())
        .then(list => {
            const platformSelect = document.getElementById("platformFilter");
            list.forEach(p => {
                let op = document.createElement("option");
                op.value = p;
                op.textContent = p;
                platformSelect.appendChild(op);
            });
        });
}

// Apply filters + sorting
function applyFilters() {
    const search = document.getElementById("searchInput").value.toLowerCase();
    const genre = document.getElementById("genreFilter").value;
    const platform = document.getElementById("platformFilter").value;
    const sort = document.getElementById("sortSelect").value;

    filteredGames = allGames.filter(g => {
        const matchesSearch = g.nama_game.toLowerCase().includes(search);
        const matchesGenre = !genre || g.genre === genre;
        const matchesPlatform = !platform || g.platform === platform;
        return matchesSearch && matchesGenre && matchesPlatform;
    });

    sortGames(sort);
    displayGames();
    showActiveFilters(search, genre, platform, sort);
}

// Sorting
function sortGames(type) {
    switch(type) {
        case "newest": 
            filteredGames.sort((a,b) => b.id - a.id); 
            break;
        case "cheapest": 
            filteredGames.sort((a,b) => a.harga - b.harga); 
            break;
        case "expensive": 
            filteredGames.sort((a,b) => b.harga - a.harga); 
            break;
        case "a-z": 
            filteredGames.sort((a,b) => a.nama_game.localeCompare(b.nama_game)); 
            break;
        case "z-a": 
            filteredGames.sort((a,b) => b.nama_game.localeCompare(a.nama_game)); 
            break;
    }
}

// Display games
function displayGames() {
    const container = document.getElementById("gameContainer");
    const badge = document.getElementById("gameCount");

    if (filteredGames.length === 0) {
        container.innerHTML = `
            <div class="no-results">
                <i class="bi bi-emoji-frown" style="font-size: 4rem; color: var(--accent-color);"></i>
                <h4>😕 No games found</h4>
                <p class="text-muted">Try adjusting your filters</p>
            </div>`;
        badge.textContent = "0 games";
        return;
    }

    let html = "";

    filteredGames.forEach(g => {
        const img = g.gambar ? `../assets/images/games/${g.gambar}` : "../assets/images/noimage.png";

        html += `
        <div class="col-md-4 mb-4">
            <div class="game-card bg-white">
                <img src="${img}" class="img-fluid">
                <div class="p-3 game-card-body">
                    <h5 class="fw-bold">${g.nama_game}</h5>
                    <p class="text-muted mb-2">
                        <span class="badge bg-secondary"><i class="bi bi-collection"></i> ${g.genre}</span>
                        <span class="badge bg-info"><i class="bi bi-laptop"></i> ${g.platform}</span>
                    </p>
                    <p class="fw-bold text-success fs-5">
                        <i class="bi bi-tag-fill"></i> Rp ${formatRupiah(g.harga)}
                    </p>
                    <a href="detail.php?id=${g.id}" class="btn btn-dark w-100">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>`;
    });

    container.innerHTML = html;
    badge.textContent = `${filteredGames.length} game${filteredGames.length > 1 ? 's' : ''}`;
}

// Show active filters
function showActiveFilters(search, genre, platform, sort) {
    let text = "";
    const div = document.getElementById("activeFilters");

    if (search) text += `<span class="filter-badge" onclick="clearSearch()"><i class="bi bi-search"></i> ${search} ✕</span>`;
    if (genre) text += `<span class="filter-badge" onclick="clearGenre()"><i class="bi bi-collection"></i> ${genre} ✕</span>`;
    if (platform) text += `<span class="filter-badge" onclick="clearPlatform()"><i class="bi bi-laptop"></i> ${platform} ✕</span>`;

    if (sort !== "newest") {
        const labels = {
            "cheapest": "Cheapest First",
            "expensive": "Most Expensive",
            "a-z": "A - Z",
            "z-a": "Z - A"
        };
        text += `<span class="filter-badge"><i class="bi bi-sort-down"></i> ${labels[sort]}</span>`;
    }

    div.innerHTML = text ? `<strong>Active:</strong> ${text}` : "";
}

// Clear single filters
function clearSearch() {
    document.getElementById("searchInput").value = "";
    applyFilters();
}
function clearGenre() {
    document.getElementById("genreFilter").value = "";
    applyFilters();
}
function clearPlatform() {
    document.getElementById("platformFilter").value = "";
    applyFilters();
}

// Reset all
function resetFilters() {
    document.getElementById("searchInput").value = "";
    document.getElementById("genreFilter").value = "";
    document.getElementById("platformFilter").value = "";
    document.getElementById("sortSelect").value = "newest";
    applyFilters();
}

// Format Rupiah
function formatRupiah(a) {
    return parseInt(a).toLocaleString('id-ID');
}