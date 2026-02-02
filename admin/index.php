<?php
session_start();
include '../config/config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../login.php"); exit; }

// Statistik
$count_p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$count_o = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$omzet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM orders"))['total'];

// Data Grafik
$res = mysqli_query($conn, "SELECT produk_dipesan, COUNT(*) as qty FROM orders GROUP BY produk_dipesan ORDER BY qty DESC LIMIT 5");
$labels = []; $counts = [];
while($r = mysqli_fetch_assoc($res)) {
    $labels[] = explode(' (', $r['produk_dipesan'])[0];
    $counts[] = $r['qty'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin Dashboard - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card-admin { 
            background: rgba(255, 255, 255, 0.05); 
            border: 1px solid var(--emas); 
            border-radius: 15px; 
            color: var(--emas);
        }
        .stat-val { color: var(--emas-t); font-weight: bold; }
        .list-group-item { background: transparent; border-color: rgba(184, 134, 11, 0.2); color: white; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h3 class="fw-bold mb-4 text-center" style="letter-spacing: 2px;">IKHTISAR PENJUALAN</h3>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card card-admin p-4 text-center shadow">
                    <h6 class="text-uppercase small">Total Produk</h6>
                    <h2 class="stat-val mb-0"><?= $count_p ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-admin p-4 text-center shadow">
                    <h6 class="text-uppercase small">Pesanan Masuk</h6>
                    <h2 class="stat-val mb-0"><?= $count_o ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-admin p-4 text-center shadow">
                    <h6 class="text-uppercase small">Total Omzet</h6>
                    <h2 class="stat-val mb-0">Rp <?= number_format($omzet, 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-admin p-4 shadow h-100">
                    <h5 class="fw-bold mb-4 text-center">Grafik Produk Favorit</h5>
                    <canvas id="adminChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-admin p-4 shadow h-100">
                    <h5 class="fw-bold mb-4">Aktivitas Terkini</h5>
                    <div class="list-group list-group-flush">
                        <?php 
                        $recent = mysqli_query($conn, "SELECT nama_pelanggan, total_bayar FROM orders ORDER BY id DESC LIMIT 5");
                        while($row = mysqli_fetch_assoc($recent)): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between small">
                                <span><?= $row['nama_pelanggan'] ?></span>
                                <span class="text-warning">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const ctx = document.getElementById('adminChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Unit Terjual',
                data: <?= json_encode($counts) ?>,
                backgroundColor: '#ffd700',
                borderColor: '#b8860b',
                borderWidth: 1
            }]
        },
        options: {
            plugins: { legend: { labels: { color: '#b8860b', font: { family: 'Georgia' } } } },
            scales: {
                y: { ticks: { color: '#b8860b' }, grid: { color: 'rgba(184,134,11,0.1)' } },
                x: { ticks: { color: '#b8860b' }, grid: { display: false } }
            }
        }
    });
    </script>
</body>
</html>