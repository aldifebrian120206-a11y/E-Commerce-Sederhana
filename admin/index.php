<?php
session_start();
include '../config/config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../login.php"); exit; }

// Statistik Utama
$count_p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$count_o = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM orders"))['total'];
$omzet_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM orders"))['total'];
$omzet = $omzet_res ? $omzet_res : 0;

// --- KOREKSI LOGIKA GRAFIK BEST SELLER ---
$res = mysqli_query($conn, "SELECT p.nama_produk, SUM(oi.qty) as total_terjual 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.id 
                            GROUP BY p.id 
                            ORDER BY total_terjual DESC 
                            LIMIT 5");
$labels = []; $counts = [];
while($r = mysqli_fetch_assoc($res)) {
    $labels[] = strtoupper($r['nama_produk']); 
    $counts[] = (int)$r['total_terjual'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --soga-deep: #160d08; 
            --soga-card: #22140e; 
            --emas-dim: #8e6516; 
            --emas-bright: #b8924b; 
            --krem-soft: #c5b5a5; 
        }

        body { 
            background-color: var(--soga-deep); 
            background-image: linear-gradient(rgba(22, 13, 8, 0.97), rgba(22, 13, 8, 0.97)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--krem-soft);
            font-family: 'Georgia', serif;
            margin: 0;
        }

        .card-admin { 
            background: var(--soga-card); 
            border: 1px solid var(--emas-dim); 
            border-radius: 15px; 
            color: var(--krem-soft);
            transition: 0.3s;
        }
        
        .card-admin:hover {
            border-color: var(--emas-bright);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
        }

        .stat-val { 
            color: var(--emas-bright); 
            font-weight: bold; 
            font-family: Arial, sans-serif;
            letter-spacing: 1px;
        }

        .list-group-item { 
            background: transparent; 
            border-color: rgba(142, 101, 22, 0.2); 
            color: var(--krem-soft); 
            padding: 15px 0;
        }

        h3, h5 {
            color: var(--emas-bright);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .aktivitas-container::-webkit-scrollbar { width: 5px; }
        .aktivitas-container::-webkit-scrollbar-thumb { background: var(--emas-dim); border-radius: 10px; }
        .aktivitas-container { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h3 class="fw-bold mb-2">IKHTISAR PENJUALAN</h3>
            <div class="mx-auto" style="width: 60px; height: 2px; background: var(--emas-bright);"></div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card card-admin p-4 text-center shadow">
                    <i class="bi bi-box-seam mb-2" style="font-size: 1.5rem; color: var(--emas-dim);"></i>
                    <h6 class="text-uppercase small opacity-75">Total Produk</h6>
                    <h2 class="stat-val mb-0"><?= $count_p ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-admin p-4 text-center shadow">
                    <i class="bi bi-cart-check mb-2" style="font-size: 1.5rem; color: var(--emas-dim);"></i>
                    <h6 class="text-uppercase small opacity-75">Pesanan Masuk</h6>
                    <h2 class="stat-val mb-0"><?= $count_o ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-admin p-4 text-center shadow">
                    <i class="bi bi-currency-dollar mb-2" style="font-size: 1.5rem; color: var(--emas-dim);"></i>
                    <h6 class="text-uppercase small opacity-75">Total Omzet</h6>
                    <h2 class="stat-val mb-0">Rp <?= number_format($omzet, 0, ',', '.') ?></h2>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-admin p-4 shadow h-100">
                    <h5 class="fw-bold mb-4 text-center">Top 5 Produk Terlaris</h5>
                    <div style="position: relative; height: 350px;">
                        <canvas id="adminChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-admin p-4 shadow h-100">
                    <h5 class="fw-bold mb-4">Aktivitas Terkini</h5>
                    <div class="list-group list-group-flush aktivitas-container">
                        <?php 
                        // Menggunakan nama_penerima agar menampilkan nama pembeli asli (Arif), bukan "admin"
                        $recent = mysqli_query($conn, "SELECT nama_penerima, total_bayar, status FROM orders ORDER BY id DESC LIMIT 6");
                        if(mysqli_num_rows($recent) > 0):
                            while($row = mysqli_fetch_assoc($recent)): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold" style="font-size: 0.85rem; color: var(--krem-soft);"><?= strtoupper($row['nama_penerima']) ?></span>
                                    <span style="color: var(--emas-bright); font-size: 0.85rem; font-weight: bold;">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="opacity-50 text-uppercase" style="font-size: 0.65rem;">Status</small>
                                    <small class="badge border border-warning text-warning" style="font-size: 0.55rem;"><?= $row['status'] ?></small>
                                </div>
                            </div>
                            <?php endwhile; 
                        else: ?>
                            <p class="text-center opacity-50 small mt-4">Belum ada aktivitas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    const ctx = document.getElementById('adminChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, '#b8924b'); 
    gradient.addColorStop(1, '#160d08'); 

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Total Terjual (Unit)',
                data: <?= json_encode($counts) ?>,
                backgroundColor: gradient,
                borderColor: '#b8924b',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#22140e',
                    titleColor: '#b8924b',
                    bodyColor: '#c5b5a5',
                    borderColor: '#8e6516',
                    borderWidth: 1
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { color: '#8e6516', stepSize: 1 }, 
                    grid: { color: 'rgba(142, 101, 22, 0.1)' } 
                },
                x: { 
                    ticks: { 
                        color: '#c5b5a5',
                        font: { size: 10 }
                    }, 
                    grid: { display: false } 
                }
            }
        }
    });
    </script>
</body>
</html>