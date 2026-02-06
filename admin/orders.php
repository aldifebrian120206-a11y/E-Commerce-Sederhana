<?php
session_start();
include '../config/config.php';

// --- LOGIKA PEMBERSIH ---
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    unset($_SESSION['cart']);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// QUERY DIPERBAIKI: Mengambil nama_penerima agar sinkron dengan input user
$query_str = "SELECT o.id, o.nama_penerima, o.no_telp, o.alamat, o.total_bayar, o.status, 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Admin Batik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --soga-deep: #160d08; 
            --soga-card: #22140e; 
            --emas-dim: #8e6516; 
            --emas-bright: #b8924b; 
            --krem-soft: #c5b5a5; 
        }

        body { 
            background-color: var(--soga-deep) !important; 
            background-image: linear-gradient(rgba(22, 13, 8, 0.96), rgba(22, 13, 8, 0.96)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png') !important; 
            color: var(--krem-soft) !important; 
            font-family: 'Georgia', serif; 
            margin: 0;
        }

        .card-custom { 
            background: var(--soga-card) !important; 
            border: 1px solid var(--emas-dim) !important; 
            border-radius: 15px; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(142, 101, 22, 0.2);
        }

        .table { 
            color: var(--krem-soft) !important; 
            margin-bottom: 0;
            border-color: rgba(142, 101, 22, 0.1) !important;
        }

        .table thead th { 
            background-color: var(--emas-dim) !important; 
            color: var(--soga-deep) !important; 
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 15px;
            border: none;
        }

        .table td { 
            background-color: transparent !important; 
            color: var(--krem-soft) !important;
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(142, 101, 22, 0.1);
        }

        .table tbody tr:hover {
            background-color: rgba(184, 146, 75, 0.03) !important;
        }

        .btn-wa { 
            background: rgba(37, 211, 102, 0.1); 
            color: #25d366 !important; 
            border: 1px solid rgba(37, 211, 102, 0.3);
            padding: 4px 10px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-size: 0.75rem; 
            display: inline-flex;
            align-items: center;
            margin-top: 5px;
            transition: 0.3s;
        }
        .btn-wa:hover { background: #25d366; color: white !important; }

        .badge-status { 
            background: rgba(184, 146, 75, 0.1);
            border: 1px solid var(--emas-bright); 
            color: var(--emas-bright); 
            padding: 5px 10px; 
            border-radius: 4px; 
            font-size: 0.7rem; 
            font-weight: bold;
            letter-spacing: 1px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: 0.3s;
            border: 1px solid transparent;
        }
        .btn-info-dark { color: #0dcaf0; border-color: rgba(13, 202, 240, 0.3); background: transparent; }
        .btn-info-dark:hover { background: #0dcaf0; color: var(--soga-deep) !important; }
        
        .btn-warn-dark { color: #ffc107; border-color: rgba(255, 193, 7, 0.3); background: transparent; }
        .btn-warn-dark:hover { background: #ffc107; color: var(--soga-deep) !important; }
        
        .btn-success-dark { color: #198754; border-color: rgba(25, 135, 84, 0.3); background: transparent; }
        .btn-success-dark:hover { background: #198754; color: white !important; }
        
        .btn-danger-dark { color: #ea868f; border-color: rgba(234, 134, 143, 0.3); background: transparent; }
        .btn-danger-dark:hover { background: #ea868f; color: white !important; }

        .text-emas-light { color: var(--emas-bright); letter-spacing: 1px; }
        .italic { font-style: italic; color: var(--krem-soft) !important; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-5 px-lg-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-emas-light">DAFTAR PESANAN PELANGGAN</h2>
            <div class="mx-auto" style="width: 80px; height: 2px; background: var(--emas-dim);"></div>
        </div>

        <div class="card-custom">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Pelanggan</th>
                            <th>Alamat Pengiriman</th>
                            <th>Rincian Produk</th>
                            <th>Total Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Perbarui Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td class="text-center opacity-50"><?= $no++ ?></td>
                            <td>
                                <div class="fw-bold" style="color:var(--emas-bright)">
                                    <?= htmlspecialchars(strtoupper($row['nama_penerima'])) ?>
                                </div>
                                <a href="https://wa.me/<?= $row['no_telp'] ?>" target="_blank" class="btn-wa">
                                    <i class="bi bi-whatsapp me-1"></i> <?= $row['no_telp'] ?>
                                </a>
                            </td>
                            <td>
                                <div class="small opacity-75" style="max-width: 200px; line-height: 1.3;">
                                    <i class="bi bi-geo-alt-fill me-1 text-warning" style="font-size: 0.7rem;"></i>
                                    <?= $row['alamat'] ?>
                                </div>
                            </td>
                            <td>
                                <div class="small" style="max-width: 250px; line-height: 1.4; opacity: 0.8;">
                                    <?= $row['rincian_produk'] ?>
                                </div>
                            </td>
                            <td class="fw-bold" style="font-family: Arial;">
                                Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <span class="badge-status"><?= strtoupper($row['status']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="update_status.php?id=<?= $row['id'] ?>&s=DIKEMAS" class="btn-action btn-info-dark" title="Kemas Barang">
                                        <i class="bi bi-box-seam"></i>
                                    </a>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&s=DIKIRIM" class="btn-action btn-warn-dark" title="Kirim Barang">
                                        <i class="bi bi-truck"></i>
                                    </a>
                                    <a href="update_status.php?id=<?= $row['id'] ?>&s=SELESAI" class="btn-action btn-success-dark" title="Selesaikan Pesanan">
                                        <i class="bi bi-check2-circle"></i>
                                    </a>
                                    <div style="width: 1px; background: rgba(142, 101, 22, 0.2); margin: 0 5px;"></div>
                                    <a href="hapus_order.php?id=<?= $row['id'] ?>" class="btn-action btn-danger-dark" onclick="return confirm('Hapus data pesanan ini?')">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($query) == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 opacity-50 italic">Tidak ada pesanan aktif saat ini.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>