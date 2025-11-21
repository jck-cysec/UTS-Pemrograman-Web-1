<?php
// user/logout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua session
$_SESSION = [];

// Hapus cookie session (jika ada)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_unset();
session_destroy();

// Redirect ke landing page
header("Location: ../index.php");
exit;
