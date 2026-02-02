<?php
session_start();
include '../config/config.php';

// Cek akses admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Cek parameter ID dan Status (s)
if (isset($_GET['id']) && isset($_GET['s'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $status_baru = mysqli_real_escape_string($conn, $_GET['s']);

    // Update status di database
    $sql = "UPDATE orders SET status = '$status_baru' WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        // Berhasil, balik ke halaman orders
        header("Location: orders.php");
    } else {
        echo "Gagal memperbarui status: " . mysqli_error($conn);
    }
} else {
    // Jika akses file langsung tanpa parameter, balik ke orders
    header("Location: orders.php");
}
?>