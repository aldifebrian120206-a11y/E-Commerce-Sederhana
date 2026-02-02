<?php
session_start();

// Cek apakah yang logout ini Admin atau User
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $redirect = "login.php";
} else {
    $redirect = "login.php";
}

// Hapus semua session
session_unset();
session_destroy();

// Arahkan kembali sesuai role-nya tadi
header("Location: " . $redirect);
exit;