<?php
session_start();
if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit; }
include '../config/config.php';
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin - Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <h3 class="fw-bold mb-4">PESANAN MASUK</h3>
        <div class="card card-custom p-0 overflow-hidden shadow-lg">
            <table class="table align-middle mb-0">
                <thead style="background: rgba(184, 134, 11, 0.1);">
                    <tr style="border-bottom: 2px solid var(--emas);">
                        <th class="ps-4 py-3">ID</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($o = mysqli_fetch_assoc($orders)): ?>
                    <tr style="border-color: rgba(184, 134, 11, 0.2);">
                        <td class="ps-4">#<?= $o['id']; ?></td>
                        <td class="fw-bold text-white"><?= $o['nama_pelanggan']; ?></td>
                        <td class="small"><?= $o['produk_dipesan']; ?></td>
                        <td class="fw-bold">Rp <?= number_format($o['total_bayar']); ?></td>
                        <td><span class="badge" style="background: var(--emas); color: var(--soga);"><?= $o['metode_pembayaran']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>