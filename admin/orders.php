<?php
session_start();
include '../config/config.php';

// --- TAMBAHAN LOGIKA PEMBERSIH BUG VISUAL ---
// Jika yang masuk adalah admin, kita paksa hapus session keranjang agar angka di navbar hilang
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    unset($_SESSION['cart']);
}
// --------------------------------------------

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Query hanya mengambil pesanan yang statusnya BUKAN 'SELESAI'
$query_str = "SELECT o.*, 
              (SELECT GROUP_CONCAT(CONCAT(p.nama_produk, ' (', oi.qty, ')') SEPARATOR ', ') 
               FROM order_items oi 
               JOIN products p ON oi.product_id = p.id 
               WHERE oi.order_id = o.id) as rincian_produk
              FROM orders o 
              WHERE o.status != 'SELESAI' 
              ORDER BY o.id DESC";
$query = mysqli_query($conn, $query_str);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --soga: #2c1810; --soga-m: #3d251c; --emas: #b8860b; --emas-t: #ffd700; --krem: #f5deb3; }
        body { background: var(--soga); background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); color: var(--krem); font-family: 'Georgia', serif; }
        .card-custom { background: var(--soga-m); border: 2px solid var(--emas); border-radius: 15px; padding: 20px; }
        .table { color: var(--krem) !important; border-color: var(--emas) !important; }
        .table > :not(caption) > * > * { background-color: var(--soga-m) !important; color: var(--krem) !important; border-bottom: 1px solid rgba(184,134,11,0.2); }
        .table th { background-color: var(--emas) !important; color: var(--soga) !important; text-transform: uppercase; font-size: 0.8rem; }
        .btn-wa { background: #25d366; color: white; padding: 3px 8px; border-radius: 5px; text-decoration: none; font-size: 0.7rem; }
        .badge-status { border: 1px solid var(--emas-t); color: var(--emas-t); padding: 4px 8px; border-radius: 5px; font-size: 0.7rem; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid py-5 px-4">
        <h2 class="text-center mb-4 fw-bold" style="color: var(--emas-t);">DAFTAR PESANAN PELANGGAN</h2>
        <div class="card-custom">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Rincian Produk</th>
                            <th>Total Bayar</th>
                            <th>Status Saat Ini</th>
                            <th class="text-center">Aksi Perubahan Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="fw-bold text-emas" style="color:var(--emas-t)"><?= $row['nama_pelanggan'] ?></div>
                                <a href="https://wa.me/<?= $row['no_telp'] ?>" class="btn-wa"><i class="bi bi-whatsapp"></i> <?= $row['no_telp'] ?></a>
                            </td>
                            <td class="small italic"><?= $row['rincian_produk'] ?></td>
                            <td class="fw-bold">Rp<?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                            <td><span class="badge-status"><?= strtoupper($row['status']) ?></span></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="update_status.php?id=<?= $row['id'] ?>&s=DIKEMAS" class="btn btn-sm btn-info" title="Kemas"><i class="bi bi-box-seam"></i></a>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&s=DIKIRIM" class="btn btn-sm btn-warning" title="Kirim"><i class="bi bi-truck"></i></a>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&s=SELESAI" class="btn btn-sm btn-success" title="Sampai"><i class="bi bi-check-all"></i></a>
                                    <a href="hapus_order.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>