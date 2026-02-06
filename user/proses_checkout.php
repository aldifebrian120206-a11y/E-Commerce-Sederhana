<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['login_user']) || empty($_SESSION['cart'])) {
    header("Location: katalog.php");
    exit;
}

// 1. TANGKAP DATA
// $username_session (misal: 'admin') digunakan agar muncul di RIWAYAT
$username_session = $_SESSION['login_user']; 

// $nama_input (misal: 'Arif') digunakan agar muncul di ADMIN
$nama_input      = mysqli_real_escape_string($conn, $_POST['nama']); 
$no_telp         = mysqli_real_escape_string($conn, $_POST['no_telp']);
$alamat_lengkap  = mysqli_real_escape_string($conn, $_POST['alamat_lengkap']);
$kurir           = mysqli_real_escape_string($conn, $_POST['kurir']);
$metode_bayar    = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
$tgl_order       = date('Y-m-d H:i:s');

// 2. HITUNG TOTAL
$total_bayar = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($conn, "SELECT harga FROM products WHERE id='$id'");
    $p = mysqli_fetch_assoc($res);
    $total_bayar += ($p['harga'] * $qty);
}

// 3. SOLUSI FIX (Tanpa tambah kolom username):
// Kita masukkan username login ke 'nama_pelanggan' (supaya muncul di riwayat user)
// Kita masukkan nama asli (Arif) ke 'nama_penerima' (supaya admin bisa lihat nama aslinya)

$query_order = "INSERT INTO orders (nama_pelanggan, nama_penerima, no_telp, alamat, total_bayar, tgl_order, kurir, metode_bayar, status) 
                VALUES ('$username_session', '$nama_input', '$no_telp', '$alamat_lengkap', '$total_bayar', '$tgl_order', '$kurir', '$metode_bayar', 'PROSES')";

if (mysqli_query($conn, $query_order)) {
    $order_id = mysqli_insert_id($conn);

    foreach ($_SESSION['cart'] as $product_id => $qty) {
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, qty) VALUES ('$order_id', '$product_id', '$qty')");
    }

    unset($_SESSION['cart']);

    echo "<script>
            alert('Pesanan Berhasil!');
            window.location.href = 'riwayat.php';
          </script>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>