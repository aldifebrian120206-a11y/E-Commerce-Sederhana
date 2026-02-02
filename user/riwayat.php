<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$user_sekarang = $_SESSION['login_user'];

$query_str = "SELECT o.*, 
              (SELECT GROUP_CONCAT(CONCAT(p.nama_produk, ' (', oi.qty, ')') SEPARATOR '<br>') 
               FROM order_items oi 
               JOIN products p ON oi.product_id = p.id 
               WHERE oi.order_id = o.id) as rincian_produk
              FROM orders o 
              WHERE o.nama_pelanggan = '$user_sekarang'
              ORDER BY o.id DESC";

$query = mysqli_query($conn, $query_str);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --soga-gelap: #2c1810; 
            --soga-tabel: #3d251c; 
            --emas: #b8860b; 
            --emas-t: #ffd700; 
            --krem: #f5deb3; 
        }

        body { 
            background: var(--soga-gelap); 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--emas-t); 
            font-family: 'Georgia', serif; 
        }

        .card-riwayat { 
            background: var(--soga-tabel); 
            border: 2px solid var(--emas); 
            border-radius: 15px; 
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        /* TABEL WARNA COKELAT */
        .table { 
            color: var(--emas-t) !important; 
            margin-bottom: 0;
            background-color: transparent !important;
        }

        .table > :not(caption) > * > * {
            background-color: var(--soga-tabel) !important;
            color: var(--emas-t) !important;
            border-bottom: 1px solid rgba(184, 134, 11, 0.3) !important;
            padding: 15px;
        }

        .table th { 
            background: var(--emas) !important; 
            color: var(--soga-gelap) !important; 
            text-transform: uppercase; 
            font-size: 0.8rem; 
            border: none;
            text-align: center;
        }

        /* WARNA BADGE STATUS (DIBIARKAN TETAP BERWARNA) */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
            display: inline-block;
            min-width: 110px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .status-proses { background: #6c757d; color: white; }
        .status-dikemas { background: #0dcaf0; color: #000; }
        .status-dikirim { background: #ffc107; color: #000; }
        .status-selesai { background: #198754; color: white; }
        
        .produk-list { font-size: 0.8rem; color: var(--krem); font-style: italic; }
        .alamat-text { font-size: 0.75rem; color: var(--krem); opacity: 0.8; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid px-4 py-5">
        <h2 class="text-center mb-5 fw-bold" style="color: var(--emas-t); letter-spacing: 3px;">
            <i class="bi bi-clock-history me-2"></i> RIWAYAT PESANAN
        </h2>
        
        <?php if (mysqli_num_rows($query) > 0): ?>
            <div class="card-riwayat shadow-lg">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Penerima & Alamat</th>
                                <th>Produk</th>
                                <th>Total Bayar</th>
                                <th>Status Pengiriman</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td class="text-center small"><?= date('d M Y', strtotime($row['tgl_order'])) ?></td>
                                <td>
                                    <div class="fw-bold text-uppercase" style="color: #fff;"><?= $row['nama_pelanggan'] ?></div>
                                    <div class="alamat-text mt-1"><?= nl2br($row['alamat']) ?></div>
                                </td>
                                <td class="produk-list"><?= $row['rincian_produk'] ?></td>
                                <td class="fw-bold">
                                    <div style="color: var(--emas-t);">Rp<?= number_format($row['total_bayar'], 0, ',', '.') ?></div>
                                    <div class="small text-muted" style="font-size: 0.7rem;"><?= $row['metode_bayar'] ?></div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $s = strtoupper($row['status']);
                                        $class = "status-proses";
                                        $icon = "bi-clock";

                                        if($s == 'DIKEMAS') { $class = "status-dikemas"; $icon = "bi-box-seam"; }
                                        elseif($s == 'DIKIRIM') { $class = "status-dikirim"; $icon = "bi-truck"; }
                                        elseif($s == 'SELESAI') { $class = "status-selesai"; $icon = "bi-check-circle"; }
                                    ?>
                                    <div class="status-badge <?= $class ?>">
                                        <i class="bi <?= $icon ?> me-1"></i> <?= $s ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x fs-1 text-muted"></i>
                <p class="mt-3">Belum ada riwayat pesanan.</p>
                <a href="katalog.php" class="btn btn-outline-warning">Belanja Sekarang</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>