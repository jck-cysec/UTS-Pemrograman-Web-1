<?php
include "session.php";

if (!isLoggedIn() || !isAdmin()) {
    // Jika belum login atau bukan admin, redirect ke login admin
    header("Location: ../admin/login.php");
    exit;
}
?>
