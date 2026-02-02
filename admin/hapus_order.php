<?php
session_start();
include '../config/config.php';

// 1. Keamanan: Cek apakah yang akses adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// 2. Cek apakah ada parameter ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 3. Hapus data di tabel order_items dulu (karena ada relasi/foreign key)
    // Ini penting agar tidak terjadi error constraint di database
    mysqli_query($conn, "DELETE FROM order_items WHERE order_id = '$id'");

    // 4. Hapus data utama di tabel orders
    $query_hapus = "DELETE FROM orders WHERE id = '$id'";

    if (mysqli_query($conn, $query_hapus)) {
        // Jika berhasil, balikkan ke halaman orders dengan pesan sukses
        echo "<script>
                alert('Pesanan berhasil dihapus.');
                window.location.href = 'orders.php';
              </script>";
    } else {
        // Jika gagal hapus
        echo "Gagal menghapus pesanan: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses file ini tanpa ID, lempar balik ke orders.php
    header("Location: orders.php");
    exit;
}
?>