<?php
session_start(); // Wajib mulai session dulu agar bisa dihapus

// 1. Hapus semua data session
session_unset();

// 2. Hancurkan session yang ada di server
session_destroy();

// 3. Pastikan tidak ada data yang tersisa di array $_SESSION
$_SESSION = array();

// 4. Lempar kembali ke halaman login
header("Location: login.php");
exit();
?>