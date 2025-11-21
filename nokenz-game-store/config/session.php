<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|----------------------------------------
| Session Timeout (Opsional)
|----------------------------------------
| Jika user tidak melakukan aktivitas selama 1 jam,
| maka session akan dihapus (logout otomatis).
*/
$timeout_duration = 60 * 60; // 1 jam

if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {
        session_unset();
        session_destroy();
        session_start();
    }
}

// Update last activity timestamp
$_SESSION['LAST_ACTIVITY'] = time();

/*
|----------------------------------------
| Helper Functions (Bisa digunakan global)
|----------------------------------------
*/

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function isAdmin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

function isUser() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'user');
}

?>