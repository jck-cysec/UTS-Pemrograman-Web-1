<?php
header("Content-Type: application/json");
include "../config/connect.php";
include "../config/session.php";

$action = $_GET['action'] ?? '';

// Helper function untuk response
function sendResponse($status, $message = "", $data = []) {
    echo json_encode(array_merge([
        "status" => $status,
        "message" => $message
    ], $data));
    exit;
}

/*
|--------------------------------------------------------------------------
| 1. REGISTER USER
|--------------------------------------------------------------------------
*/
if ($action == "register") {

    $nama     = $_POST['nama'] ?? '';
    $email    = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($nama == '' || $email == '' || $username == '' || $password == '') {
        sendResponse(false, "Semua field wajib diisi");
    }

    $hash = md5($password);

    $sql = "INSERT INTO users (nama, email, username, password, role) 
            VALUES ('$nama', '$email', '$username', '$hash', 'user')";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "Register berhasil");
    } else {
        sendResponse(false, "Email/Username sudah digunakan");
    }
}

/*
|--------------------------------------------------------------------------
| 2. LOGIN USER/ADMIN
|--------------------------------------------------------------------------
*/
if ($action == "login") {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $hash = md5($password);

    $query = mysqli_query($conn, 
        "SELECT * FROM users WHERE username='$username' AND password='$hash'"
    );

    if (mysqli_num_rows($query) > 0) {

        $data = mysqli_fetch_assoc($query);

        // SET SESSION
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id']   = $data['id'];
        $_SESSION['username']  = $data['username'];
        $_SESSION['nama']      = $data['nama']; 
        $_SESSION['role']      = $data['role'];

        sendResponse(true, "Login berhasil", [
            "role" => $data['role']
        ]);

    } else {
        sendResponse(false, "Username/Password salah");
    }
}

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
if ($action == "logout") {
    session_unset();
    session_destroy();
    sendResponse(true, "Logout berhasil");
}

/*
|--------------------------------------------------------------------------
| 3. GET ALL GAMES
|--------------------------------------------------------------------------
*/
if ($action == "get_games") {

    $query = mysqli_query($conn, "SELECT * FROM games ORDER BY id DESC");

    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

/*
|--------------------------------------------------------------------------
| 4. GET GAME BY ID
|--------------------------------------------------------------------------
*/
if ($action == "get_game") {

    $id = $_GET['id'] ?? 0;

    $query = mysqli_query($conn, "SELECT * FROM games WHERE id = $id");

    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}

/*
|--------------------------------------------------------------------------
| 5. ADMIN: INSERT GAME
|--------------------------------------------------------------------------
*/
if ($action == "insert_game") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $nama   = $_POST['nama_game'];
    $genre  = $_POST['genre'];
    $plat   = $_POST['platform'];
    $harga  = $_POST['harga'];
    $desc   = $_POST['deskripsi'];
    $gambar = $_POST['gambar'];

    $sql = "INSERT INTO games (nama_game, genre, platform, harga, deskripsi, gambar) 
            VALUES ('$nama', '$genre', '$plat', '$harga', '$desc', '$gambar')";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "Game berhasil ditambahkan");
    } else {
        sendResponse(false, "Gagal menambahkan game");
    }
}

/*
|--------------------------------------------------------------------------
| 6. ADMIN: UPDATE GAME
|--------------------------------------------------------------------------
*/
if ($action == "update_game") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $id     = $_POST['id'];
    $nama   = $_POST['nama_game'];
    $genre  = $_POST['genre'];
    $plat   = $_POST['platform'];
    $harga  = $_POST['harga'];
    $desc   = $_POST['deskripsi'];
    $gambar = $_POST['gambar'];

    $sql = "UPDATE games SET 
            nama_game='$nama',
            genre='$genre',
            platform='$plat',
            harga='$harga',
            deskripsi='$desc',
            gambar='$gambar'
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "Game berhasil diupdate");
    } else {
        sendResponse(false, "Gagal mengupdate game");
    }
}

/*
|--------------------------------------------------------------------------
| 7. ADMIN: DELETE GAME
|--------------------------------------------------------------------------
*/
if ($action == "delete_game") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    // Ambil dari $_POST atau parse dari php://input
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Untuk DELETE method, ambil dari php://input
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'] ?? 0;
    } else {
        // Untuk POST method
        $id = $_POST['id'] ?? 0;
    }

    if ($id == 0) {
        sendResponse(false, "Invalid ID");
    }

    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM `games` WHERE `id` = '$id'";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "Game berhasil dihapus");
    } else {
        sendResponse(false, "Gagal menghapus game: " . mysqli_error($conn));
    }
}

/*
|--------------------------------------------------------------------------
| HELPER FUNCTION: Check if user is admin
|--------------------------------------------------------------------------
*/
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
    }
}

/*
|--------------------------------------------------------------------------
| ADMIN: DELETE USER
|--------------------------------------------------------------------------
*/
if ($action == "delete_user") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $id = $_POST['id'] ?? 0;

    if ($id == 0) {
        sendResponse(false, "Invalid user ID");
    }

    // Cek apakah user yang akan dihapus bukan admin
    $check = mysqli_query($conn, "SELECT role FROM users WHERE id = $id");
    $user = mysqli_fetch_assoc($check);

    if (!$user) {
        sendResponse(false, "User not found");
    }

    if ($user['role'] == 'admin') {
        sendResponse(false, "Cannot delete admin user");
    }

    // Hapus data terkait user
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $id");
    mysqli_query($conn, "DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = $id)");
    mysqli_query($conn, "DELETE FROM orders WHERE user_id = $id");

    // Hapus user
    $sql = "DELETE FROM users WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "User deleted successfully");
    } else {
        sendResponse(false, "Failed to delete user");
    }
}

/*
|--------------------------------------------------------------------------
| ADMIN: GET ALL ORDERS
|--------------------------------------------------------------------------
*/
if ($action == "get_all_orders") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $query = mysqli_query($conn, 
        "SELECT 
            o.id,
            o.total_harga,
            o.status,
            o.payment_method,
            o.tanggal_order,
            u.nama
         FROM orders o
         JOIN users u ON o.user_id = u.id
         ORDER BY o.id DESC"
    );

    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN: GET ORDER DETAIL
|--------------------------------------------------------------------------
*/
if ($action == "get_order_detail") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $order_id = $_GET['order_id'] ?? 0;

    if ($order_id == 0) {
        sendResponse(false, "Invalid order ID");
    }

    // Get order info
    $order_query = mysqli_query($conn,
        "SELECT 
            o.id,
            o.total_harga,
            o.status,
            o.payment_method,
            o.tanggal_order,
            u.nama,
            u.email
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.id = $order_id"
    );

    $order = mysqli_fetch_assoc($order_query);

    if (!$order) {
        sendResponse(false, "Order not found");
    }

    // Get order items
    $items_query = mysqli_query($conn,
        "SELECT 
            oi.harga,
            oi.qty,
            g.nama_game
         FROM order_items oi
         JOIN games g ON oi.game_id = g.id
         WHERE oi.order_id = $order_id"
    );

    $items = [];
    while ($row = mysqli_fetch_assoc($items_query)) {
        $items[] = $row;
    }

    echo json_encode([
        'order' => $order,
        'items' => $items
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN: UPDATE ORDER STATUS
|--------------------------------------------------------------------------
*/
if ($action == "update_order_status") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $order_id = $_POST['order_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    if ($order_id == 0) {
        sendResponse(false, "Invalid order ID");
    }

    if ($status == '') {
        sendResponse(false, "Status cannot be empty");
    }

    // Validate status
    if (!in_array($status, ['pending', 'paid'])) {
        sendResponse(false, "Invalid status value");
    }

    $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "Order status updated successfully");
    } else {
        sendResponse(false, "Failed to update status");
    }
}

/*
|--------------------------------------------------------------------------
| ADMIN: DELETE ANY ORDER
|--------------------------------------------------------------------------
*/
if ($action == "delete_order_admin") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $order_id = $_POST['order_id'] ?? 0;

    if ($order_id == 0) {
        sendResponse(false, "Invalid order ID");
    }

    // Cek apakah order ada
    $check = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id");
    
    if (mysqli_num_rows($check) == 0) {
        sendResponse(false, "Order not found");
    }

    // Hapus order items terlebih dahulu
    $delete_items = mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $order_id");
    
    if (!$delete_items) {
        sendResponse(false, "Failed to delete order items");
    }

    // Hapus order
    $delete_order = mysqli_query($conn, "DELETE FROM orders WHERE id = $order_id");

    if ($delete_order) {
        sendResponse(true, "Order deleted successfully");
    } else {
        sendResponse(false, "Failed to delete order");
    }
}

/*
|--------------------------------------------------------------------------
| ADMIN: GET NEWS BY ID
|--------------------------------------------------------------------------
*/
if ($action == "get_news_by_id") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $id = $_GET['id'] ?? 0;

    if ($id == 0) {
        sendResponse(false, "Invalid news ID");
    }

    $query = mysqli_query($conn, "SELECT * FROM news WHERE id = $id");
    $news = mysqli_fetch_assoc($query);

    if (!$news) {
        sendResponse(false, "News not found");
    }

    echo json_encode($news);
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN: INSERT NEWS
|--------------------------------------------------------------------------
*/
if ($action == "insert_news") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $gambar = $_POST['gambar'] ?? '';

    if ($judul == '' || $deskripsi == '' || $tanggal == '' || $gambar == '') {
        sendResponse(false, "All fields are required");
    }

    $sql = "INSERT INTO news (judul, deskripsi, tanggal, gambar) 
            VALUES ('$judul', '$deskripsi', '$tanggal', '$gambar')";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "News added successfully");
    } else {
        sendResponse(false, "Failed to add news: " . mysqli_error($conn));
    }
}

/*
|--------------------------------------------------------------------------
| ADMIN: UPDATE NEWS
|--------------------------------------------------------------------------
*/
if ($action == "update_news") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $id = $_POST['id'] ?? 0;
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $gambar = $_POST['gambar'] ?? '';

    if ($id == 0) {
        sendResponse(false, "Invalid news ID");
    }

    if ($judul == '' || $deskripsi == '' || $tanggal == '' || $gambar == '') {
        sendResponse(false, "All fields are required");
    }

    $sql = "UPDATE news SET 
            judul = '$judul',
            deskripsi = '$deskripsi',
            tanggal = '$tanggal',
            gambar = '$gambar'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "News updated successfully");
    } else {
        sendResponse(false, "Failed to update news: " . mysqli_error($conn));
    }
}

/*
|--------------------------------------------------------------------------
| ADMIN: DELETE NEWS
|--------------------------------------------------------------------------
*/
if ($action == "delete_news") {

    if (!isAdmin()) {
        sendResponse(false, "Unauthorized");
    }

    $id = $_POST['id'] ?? 0;

    if ($id == 0) {
        sendResponse(false, "Invalid news ID");
    }

    $sql = "DELETE FROM news WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "News deleted successfully");
    } else {
        sendResponse(false, "Failed to delete news: " . mysqli_error($conn));
    }
}

/*
|--------------------------------------------------------------------------
| 8. GET NEWS (Landing Page)
|--------------------------------------------------------------------------
*/
if ($action == "get_news") {

    $q = mysqli_query($conn, "SELECT * FROM news ORDER BY tanggal DESC");

    $data = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}


/*
|--------------------------------------------------------------------------
| 9. ADD TO CART
|--------------------------------------------------------------------------
*/
if ($action == "add_cart") {

    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Silakan login terlebih dahulu");
    }

    $user_id = $_SESSION['user_id'];
    $game_id = $_POST['game_id'] ?? 0;

    if ($game_id == 0) {
        sendResponse(false, "Game ID tidak valid");
    }

    // Cek apakah game sudah ada di cart
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND game_id='$game_id'");
    
    if (mysqli_num_rows($check) > 0) {
        sendResponse(false, "Game sudah ada di keranjang");
    }

    $sql = "INSERT INTO cart (user_id, game_id) VALUES ('$user_id', '$game_id')";

    if (mysqli_query($conn, $sql)) {
        sendResponse(true, "Game berhasil ditambahkan ke keranjang");
    } else {
        sendResponse(false, "Gagal menambahkan ke keranjang: " . mysqli_error($conn));
    }
}

/*
|--------------------------------------------------------------------------
| 10. GET CART USER
|--------------------------------------------------------------------------
*/
if ($action == "get_cart") {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }

    $id = $_SESSION['user_id'];

    $q = mysqli_query($conn, 
        "SELECT 
            cart.id AS cart_id,
            games.nama_game, 
            games.harga, 
            games.gambar AS ggambar,
            games.genre,
            games.platform
         FROM cart 
         JOIN games ON cart.game_id = games.id 
         WHERE cart.user_id = $id"
    );

    $data = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

/*
|--------------------------------------------------------------------------
| 11. DELETE CART ITEM
|--------------------------------------------------------------------------
*/
if ($action == "delete_cart_item") {

    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Unauthorized");
    }

    $cart_id = $_POST['cart_id'] ?? 0;

    if ($cart_id == 0) {
        sendResponse(false, "Cart ID tidak valid");
    }

    $q = mysqli_query($conn, "DELETE FROM cart WHERE id = $cart_id");

    if ($q) {
        sendResponse(true, "Item berhasil dihapus");
    } else {
        sendResponse(false, "Gagal menghapus item");
    }
}

/*
|--------------------------------------------------------------------------
| 12. CHECKOUT
|--------------------------------------------------------------------------
*/
if ($action == "checkout") {

    // Cek user login
    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Silakan login terlebih dahulu");
    }

    $user_id = $_SESSION['user_id'];

    // Cek cart
    $cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
    
    if (!$cart) {
        sendResponse(false, "Error mengambil data cart: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($cart) == 0) {
        sendResponse(false, "Keranjang kosong");
    }

    // Hitung total
    $total = 0;
    $items = [];
    
    while ($row = mysqli_fetch_assoc($cart)) {
        $game_id = $row['game_id'];
        $g = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga, nama_game FROM games WHERE id = $game_id"));
        
        if ($g) {
            $total += $g['harga'];
            $items[] = [
                'game_id' => $game_id,
                'harga' => $g['harga']
            ];
        }
    }

    if ($total == 0 || count($items) == 0) {
        sendResponse(false, "Tidak ada item valid di keranjang");
    }

    // Insert order
    $insert_order = mysqli_query($conn, 
        "INSERT INTO orders (user_id, total_harga, status, tanggal_order) 
         VALUES ($user_id, $total, 'pending', NOW())"
    );

    if (!$insert_order) {
        sendResponse(false, "Gagal membuat order: " . mysqli_error($conn));
    }

    $order_id = mysqli_insert_id($conn);

    if (!$order_id) {
        sendResponse(false, "Gagal mendapatkan Order ID");
    }

    // Insert order items
    $success_items = 0;
    foreach ($items as $item) {
        $insert_item = mysqli_query($conn,
            "INSERT INTO order_items (order_id, game_id, harga, qty)
             VALUES ($order_id, {$item['game_id']}, {$item['harga']}, 1)"
        );
        
        if ($insert_item) {
            $success_items++;
        }
    }

    if ($success_items == 0) {
        // Rollback: hapus order jika tidak ada item yang berhasil
        mysqli_query($conn, "DELETE FROM orders WHERE id = $order_id");
        sendResponse(false, "Gagal menambahkan item ke order");
    }

    // Hapus cart
    $delete_cart = mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

    if (!$delete_cart) {
        // Order sudah dibuat, tapi cart gagal dihapus (non-critical)
        error_log("Warning: Gagal menghapus cart untuk user $user_id");
    }

    sendResponse(true, "Checkout berhasil", [
        "order_id" => $order_id,
        "total" => $total,
        "items_count" => $success_items
    ]);
}

/*
|--------------------------------------------------------------------------
| 12b. PAYMENT CONFIRM
|--------------------------------------------------------------------------
*/
if ($action == "payment_confirm") {
    
    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Unauthorized");
    }

    $order_id = $_POST['order_id'] ?? 0;
    $method   = $_POST['method'] ?? '';

    if ($order_id == 0) {
        sendResponse(false, "Order ID tidak valid");
    }

    if ($method == '') {
        sendResponse(false, "Metode pembayaran belum dipilih");
    }

    // Validasi order milik user ini
    $user_id = $_SESSION['user_id'];
    $check = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");
    
    if (mysqli_num_rows($check) == 0) {
        sendResponse(false, "Order tidak ditemukan");
    }

    // Update status order
    $update = mysqli_query($conn, 
        "UPDATE orders 
         SET status='paid', payment_method='$method' 
         WHERE id = $order_id"
    );

    if ($update) {
        sendResponse(true, "Pembayaran berhasil dikonfirmasi", [
            "order_id" => $order_id,
            "method" => $method
        ]);
    } else {
        sendResponse(false, "Gagal mengkonfirmasi pembayaran: " . mysqli_error($conn));
    }
}

/*
|--------------------------------------------------------------------------
| ORDER HISTORY
|--------------------------------------------------------------------------
*/
if ($action == "order_history") {
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $q = mysqli_query($conn,
        "SELECT id, total_harga, status, tanggal_order 
         FROM orders 
         WHERE user_id = $user_id 
         ORDER BY id DESC"
    );

    $data = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

/*
|--------------------------------------------------------------------------
| USER: DELETE OWN ORDER
|--------------------------------------------------------------------------
*/
if ($action == "delete_order") {

    if (!isset($_SESSION['user_id'])) {
        sendResponse(false, "Unauthorized");
    }

    $order_id = $_POST['order_id'] ?? 0;
    $user_id = $_SESSION['user_id'];

    if ($order_id == 0) {
        sendResponse(false, "Invalid order ID");
    }

    // Validasi order milik user ini
    $check = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");
    
    if (mysqli_num_rows($check) == 0) {
        sendResponse(false, "Order not found or unauthorized");
    }

    // Hapus order items terlebih dahulu
    $delete_items = mysqli_query($conn, "DELETE FROM order_items WHERE order_id = $order_id");
    
    if (!$delete_items) {
        sendResponse(false, "Failed to delete order items");
    }

    // Hapus order
    $delete_order = mysqli_query($conn, "DELETE FROM orders WHERE id = $order_id");

    if ($delete_order) {
        sendResponse(true, "Order deleted successfully");
    } else {
        sendResponse(false, "Failed to delete order");
    }
}

/*
|--------------------------------------------------------------------------
| ORDER ITEMS DETAIL PER ORDER
|--------------------------------------------------------------------------
*/
if ($action == "order_items") {
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $order_id = $_GET['order_id'] ?? 0;

    if ($order_id == 0) {
        echo json_encode([]);
        exit;
    }

    // Validasi order milik user
    $check = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");
    
    if (mysqli_num_rows($check) == 0) {
        echo json_encode([]);
        exit;
    }

    $q = mysqli_query($conn,
        "SELECT 
            games.nama_game,
            games.gambar,
            order_items.harga
         FROM order_items
         JOIN games ON order_items.game_id = games.id
         WHERE order_items.order_id = $order_id"
    );

    $data = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}

/*
|--------------------------------------------------------------------------
| GET HISTORY (Alternative - untuk kompatibilitas)
|--------------------------------------------------------------------------
*/
if ($action == "get_history") {
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $q = mysqli_query($conn,
        "SELECT 
            o.id,
            o.total_harga,
            o.status,
            o.tanggal_order as tanggal,
            oi.harga as harga_game,
            g.nama_game
         FROM orders o
         JOIN order_items oi ON o.id = oi.order_id
         JOIN games g ON oi.game_id = g.id
         WHERE o.user_id = $user_id 
         ORDER BY o.id DESC, g.nama_game"
    );

    $data = [];
    while ($row = mysqli_fetch_assoc($q)) {
        $data[] = $row;
    }

    echo json_encode($data);
    exit;
}


/* ============================
   GET GENRES
   ============================ */
if ($action === "get_genres") {
    $genres = [];

    // Cek apakah tabel genres ada
    $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'genres'");
    
    if (!$check_table || mysqli_num_rows($check_table) == 0) {
        // Tabel tidak ada, return array kosong
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }

    // Ambil dari tabel genres
    $q = mysqli_query($conn, "SELECT nama FROM genres ORDER BY nama ASC");
    
    if (!$q) {
        // Query error
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }
    
    while ($row = mysqli_fetch_assoc($q)) {
        if (!empty($row['nama'])) {
            $genres[] = $row['nama'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($genres);
    exit;
}

/* ============================
   GET PLATFORMS
   ============================ */
if ($action === "get_platforms") {
    $platforms = [];

    // Cek apakah tabel platforms ada
    $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'platforms'");
    
    if (!$check_table || mysqli_num_rows($check_table) == 0) {
        // Tabel tidak ada, return array kosong
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }

    // Ambil dari tabel platforms
    $q = mysqli_query($conn, "SELECT nama FROM platforms ORDER BY nama ASC");
    
    if (!$q) {
        // Query error
        header('Content-Type: application/json');
        echo json_encode([]);
        exit;
    }
    
    while ($row = mysqli_fetch_assoc($q)) {
        if (!empty($row['nama'])) {
            $platforms[] = $row['nama'];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($platforms);
    exit;
}

/* ============================
   ADD GENRE
   ============================ */
if ($action === "add_genre") {
    if (!isAdmin()) sendResponse(false, "Unauthorized");

    $genre = trim($_POST['genre'] ?? '');
    $genre = mysqli_real_escape_string($conn, $genre);

    if ($genre === '') sendResponse(false, "Genre tidak boleh kosong");

    // Cek duplikasi
    $check = mysqli_query($conn, "SELECT id FROM genres WHERE nama='$genre' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        sendResponse(false, "Genre sudah ada");
    }

    $insert = mysqli_query($conn, "INSERT INTO genres (nama) VALUES ('$genre')");
    
    if ($insert) {
        sendResponse(true, "Genre berhasil ditambahkan");
    } else {
        sendResponse(false, "Gagal menambahkan genre: " . mysqli_error($conn));
    }
}

/* ============================
   ADD PLATFORM
   ============================ */
if ($action === "add_platform") {
    if (!isAdmin()) sendResponse(false, "Unauthorized");

    $platform = trim($_POST['platform'] ?? '');
    $platform = mysqli_real_escape_string($conn, $platform);

    if ($platform === '') sendResponse(false, "Platform tidak boleh kosong");

    // Cek duplikasi
    $check = mysqli_query($conn, "SELECT id FROM platforms WHERE nama='$platform' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        sendResponse(false, "Platform sudah ada");
    }

    $insert = mysqli_query($conn, "INSERT INTO platforms (nama) VALUES ('$platform')");
    
    if ($insert) {
        sendResponse(true, "Platform berhasil ditambahkan");
    } else {
        sendResponse(false, "Gagal menambahkan platform: " . mysqli_error($conn));
    }
}

/* ============================
   UPDATE GENRE
   ============================ */
if ($action === "update_genre") {
    if (!isAdmin()) sendResponse(false, "Unauthorized");

    $old = mysqli_real_escape_string($conn, trim($_POST['old_genre'] ?? ''));
    $new = mysqli_real_escape_string($conn, trim($_POST['new_genre'] ?? ''));

    if ($old === '' || $new === '') sendResponse(false, "Input tidak valid");

    // Cek apakah new genre sudah ada (selain yang old)
    $dup = mysqli_query($conn, "SELECT id FROM genres WHERE nama='$new' AND nama!='$old' LIMIT 1");
    if (mysqli_num_rows($dup) > 0) {
        sendResponse(false, "Genre baru sudah digunakan");
    }

    // Update di tabel genres
    $update_genre = mysqli_query($conn, "UPDATE genres SET nama='$new' WHERE nama='$old'");

    // Update di tabel games juga (agar konsisten)
    mysqli_query($conn, "UPDATE games SET genre='$new' WHERE genre='$old'");

    if ($update_genre) {
        sendResponse(true, "Genre berhasil diperbarui");
    } else {
        sendResponse(false, "Gagal memperbarui genre: " . mysqli_error($conn));
    }
}

/* ============================
   UPDATE PLATFORM
   ============================ */
if ($action === "update_platform") {
    if (!isAdmin()) sendResponse(false, "Unauthorized");

    $old = mysqli_real_escape_string($conn, trim($_POST['old_platform'] ?? ''));
    $new = mysqli_real_escape_string($conn, trim($_POST['new_platform'] ?? ''));

    if ($old === '' || $new === '') sendResponse(false, "Input tidak valid");

    // Cek duplikasi
    $dup = mysqli_query($conn, "SELECT id FROM platforms WHERE nama='$new' AND nama!='$old' LIMIT 1");
    if (mysqli_num_rows($dup) > 0) {
        sendResponse(false, "Platform baru sudah digunakan");
    }

    // Update di tabel platforms
    $update_platform = mysqli_query($conn, "UPDATE platforms SET nama='$new' WHERE nama='$old'");

    // Update di tabel games juga
    mysqli_query($conn, "UPDATE games SET platform='$new' WHERE platform='$old'");

    if ($update_platform) {
        sendResponse(true, "Platform berhasil diperbarui");
    } else {
        sendResponse(false, "Gagal memperbarui platform: " . mysqli_error($conn));
    }
}

/* ============================
   DELETE GENRE
   ============================ */
if ($action === "delete_genre") {
    if (!isAdmin()) sendResponse(false, "Unauthorized");

    $genre = mysqli_real_escape_string($conn, trim($_POST['genre'] ?? ''));

    if ($genre === '') sendResponse(false, "Genre tidak valid");

    // Hapus dari tabel genres
    $delete = mysqli_query($conn, "DELETE FROM genres WHERE nama='$genre'");

    if ($delete) {
        sendResponse(true, "Genre berhasil dihapus");
    } else {
        sendResponse(false, "Gagal menghapus genre: " . mysqli_error($conn));
    }
}

/* ============================
   DELETE PLATFORM
   ============================ */
if ($action === "delete_platform") {
    if (!isAdmin()) sendResponse(false, "Unauthorized");

    $platform = mysqli_real_escape_string($conn, trim($_POST['platform'] ?? ''));

    if ($platform === '') sendResponse(false, "Platform tidak valid");

    // Hapus dari tabel platforms
    $delete = mysqli_query($conn, "DELETE FROM platforms WHERE nama='$platform'");

    if ($delete) {
        sendResponse(true, "Platform berhasil dihapus");
    } else {
        sendResponse(false, "Gagal menghapus platform: " . mysqli_error($conn));
    }
}

/* ==========================================================
   UPDATE PROFILE USER
   ========================================================== */
if ($action == "update_profile") {

    // Ambil raw JSON
    $data = json_decode(file_get_contents("php://input"), true);

    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $password = $data['password'];

    session_start();
    $user_id = $_SESSION['user_id'];

    // Cek apakah username sudah dipakai user lain
    $q1 = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' AND id!='$user_id'");
    if (mysqli_num_rows($q1) > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Username sudah digunakan orang lain."
        ]);
        exit;
    }

    // Cek apakah email sudah dipakai user lain
    $q2 = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND id!='$user_id'");
    if (mysqli_num_rows($q2) > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Email sudah digunakan orang lain."
        ]);
        exit;
    }

    // Jika password kosong → jangan update password
    if ($password == "" || $password == null) {

        $sql = "UPDATE users SET 
                    nama='$nama',
                    username='$username',
                    email='$email'
                WHERE id='$user_id'";

    } else {

        // Hash password MD5 sesuai database kamu
        $hashed = md5($password);

        $sql = "UPDATE users SET 
                    nama='$nama',
                    username='$username',
                    email='$email',
                    password='$hashed'
                WHERE id='$user_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            "success" => true,
            "message" => "Profil berhasil diperbarui."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Gagal update profil."
        ]);
    }

    exit;
}


// Default response jika action tidak ditemukan
sendResponse(false, "Invalid action: $action");
?>