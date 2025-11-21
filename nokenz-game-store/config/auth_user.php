<?php
include "session.php";

if (!isLoggedIn() || !isUser()) {
    // Jika belum login atau bukan user, kembalikan ke login user
    header("Location: ../user/login.php");
    exit;
}
?>
