<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['login_user']) || empty($_SESSION['cart'])) {
    header("Location: katalog.php");
    exit;
}

$nama_pelanggan = $_SESSION['login_user'];
$no_telp       = mysqli_real_escape_string($conn, $_POST['no_telp']);
$alamat_lengkap = mysqli_real_escape_string($conn, $_POST['alamat_lengkap']);
$kurir         = mysqli_real_escape_string($conn, $_POST['kurir']);
$metode_bayar  = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
$tgl_order     = date('Y-m-d H:i:s');

// 1. Hitung total bayar
$total_bayar = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($conn, "SELECT harga FROM products WHERE id='$id'");
    $p = mysqli_fetch_assoc($res);
    $total_bayar += ($p['harga'] * $qty);
}

// 2. Masukkan ke tabel orders (Nama kolom DISESUAIKAN dengan database kamu)
$query_order = "INSERT INTO orders (nama_pelanggan, no_telp, alamat, total_bayar, tgl_order, kurir, metode_bayar, status) 
                VALUES ('$nama_pelanggan', '$no_telp', '$alamat_lengkap', '$total_bayar', '$tgl_order', '$kurir', '$metode_bayar', 'PROSES')";

if (mysqli_query($conn, $query_order)) {
    $order_id = mysqli_insert_id($conn);

    // 3. Simpan rincian ke order_items
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty) VALUES ('$order_id', '$product_id', '$qty')");
    }

    unset($_SESSION['cart']);

    echo "<script>
            alert('Pesanan Berhasil!');
            window.location.href = 'riwayat.php';
          </script>";
} else {
    // Jika masih error, ini akan memunculkan pesan error database yang jelas
    echo "Gagal: " . mysqli_error($conn);
}
?>