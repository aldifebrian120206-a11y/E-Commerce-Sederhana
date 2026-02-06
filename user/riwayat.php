<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$user_sekarang = $_SESSION['login_user'];

// 1. Logika Konfirmasi Terima Barang
if (isset($_GET['konfirmasi_id'])) {
    $id_order = mysqli_real_escape_string($conn, $_GET['konfirmasi_id']);
    $update_query = "UPDATE orders SET status = 'SELESAI' 
                     WHERE id = '$id_order' AND nama_pelanggan = '$user_sekarang'";
    mysqli_query($conn, $update_query);
    echo "<script>alert('Pesanan telah selesai. Silakan berikan rating!'); window.location='riwayat.php';</script>";
}

// 2. Logika Simpan Rating
if (isset($_POST['submit_rating'])) {
    $id_order = mysqli_real_escape_string($conn, $_POST['order_id']);
    $star = mysqli_real_escape_string($conn, $_POST['rating']);
    mysqli_query($conn, "UPDATE orders SET rating = '$star' WHERE id = '$id_order' AND nama_pelanggan = '$user_sekarang'");
    echo "<script>alert('Terima kasih atas rating Anda!'); window.location='riwayat.php';</script>";
}

$query_str = "SELECT o.*, u.nama_lengkap 
              FROM orders o 
              LEFT JOIN users u ON o.nama_pelanggan = u.username 
              WHERE o.nama_pelanggan = '$user_sekarang' 
              ORDER BY o.id DESC";

$query = mysqli_query($conn, $query_str);

if (!$query) {
    $query_str = "SELECT * FROM orders WHERE nama_pelanggan = '$user_sekarang' ORDER BY id DESC";
    $query = mysqli_query($conn, $query_str);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --soga-gelap: #1a0f0a; 
            --soga-kartu: #2a1810; 
            --emas-tua: #8e6516; 
            --emas-muda: #d4af37; 
            --krem-batik: #c5b5a5; 
        }

        body { 
            background-color: var(--soga-gelap); 
            background-image: linear-gradient(rgba(26, 15, 10, 0.95), rgba(26, 15, 10, 0.95)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--krem-batik); 
            font-family: 'Georgia', serif; 
        }

        .card-riwayat { 
            background: var(--soga-kartu); 
            border: 1px solid var(--emas-tua); 
            border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
            overflow: hidden;
            margin-bottom: 50px;
        }

        .table { 
            color: var(--krem-batik) !important; 
            margin-bottom: 0;
            background-color: transparent !important;
            border-collapse: collapse;
        }

        .table thead th { 
            background: var(--emas-tua) !important; 
            color: var(--soga-gelap) !important;
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 2px;
            padding: 15px !important;
            border: none;
            text-align: center;
        }

        .table tbody td {
            background-color: transparent !important;
            border-bottom: 1px solid rgba(142, 101, 22, 0.2) !important;
            padding: 18px !important;
            vertical-align: middle;
            color: var(--krem-batik);
        }

        .nama-penerima { color: var(--emas-muda); font-weight: bold; font-size: 0.9rem; }
        .alamat-info { color: var(--krem-batik); opacity: 0.7; font-size: 0.8rem; font-family: sans-serif; }

        .status-badge {
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 0.65rem;
            font-weight: bold;
            display: inline-block;
            border: 1px solid;
            background: rgba(0,0,0,0.3);
            text-transform: uppercase;
        }
        .status-proses { color: #a08060; border-color: #a08060; }
        .status-dikirim { color: #ffbf00; border-color: #ffbf00; }
        .status-selesai { color: #2ecc71; border-color: #2ecc71; background: rgba(46, 204, 113, 0.1); }

        .btn-terima {
            background: transparent;
            color: var(--emas-muda);
            border: 1px solid var(--emas-muda);
            font-size: 0.7rem;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
            margin-top: 8px;
        }
        .btn-terima:hover {
            background: var(--emas-muda);
            color: var(--soga-gelap);
        }

        /* Style Rating */
        .select-rating {
            background: rgba(0,0,0,0.4);
            border: 1px solid var(--emas-tua);
            color: var(--emas-muda);
            font-size: 0.7rem;
            padding: 2px;
            border-radius: 4px;
        }

        .harga { color: var(--emas-muda); font-weight: bold; font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h2 class="text-center mb-5" style="color: var(--emas-muda); letter-spacing: 5px; font-weight: 300;">
            RIWAYAT PESANAN
        </h2>
        
        <?php if ($query && mysqli_num_rows($query) > 0): ?>
            <div class="card-riwayat">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>TANGGAL</th>
                                <th class="text-start">PENERIMA</th>
                                <th>TOTAL</th>
                                <th>STATUS / RATING</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td class="text-center small opacity-75">
                                    <?= date('d/m/Y', strtotime($row['tgl_order'])) ?>
                                </td>
                                <td>
                                    <div class="nama-penerima">
                                        <?php 
                                            if(!empty($row['nama_penerima'])) {
                                                echo strtoupper($row['nama_penerima']);
                                            } elseif(!empty($row['nama_lengkap'])) {
                                                echo strtoupper($row['nama_lengkap']);
                                            } else {
                                                echo strtoupper($row['nama_pelanggan']);
                                            }
                                        ?>
                                    </div>
                                    <div class="alamat-info mt-1"><?= nl2br($row['alamat']) ?></div>
                                </td>
                                <td class="text-center">
                                    <div class="harga">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></div>
                                    <div class="small opacity-50" style="font-size: 0.6rem;"><?= $row['metode_bayar'] ?></div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $s = strtoupper($row['status']);
                                        $class = "status-proses";
                                        if($s == 'DIKIRIM') $class = "status-dikirim";
                                        elseif($s == 'SELESAI') $class = "status-selesai";
                                    ?>
                                    
                                    <div class="status-badge <?= $class ?>">
                                        <?= $s ?>
                                    </div>

                                    <?php if($s == 'DIKIRIM'): ?>
                                        <div class="mt-1">
                                            <a href="riwayat.php?konfirmasi_id=<?= $row['id'] ?>" 
                                               class="btn-terima" 
                                               onclick="return confirm('Konfirmasi barang telah diterima?')">
                                               TERIMA BARANG
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($s == 'SELESAI'): ?>
                                        <div class="mt-2">
                                            <?php if(empty($row['rating'])): ?>
                                                <form method="POST" class="d-flex flex-column align-items-center">
                                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                    <select name="rating" class="select-rating mb-1">
                                                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                                        <option value="4">⭐⭐⭐⭐ (4)</option>
                                                        <option value="3">⭐⭐⭐ (3)</option>
                                                        <option value="2">⭐⭐ (2)</option>
                                                        <option value="1">⭐ (1)</option>
                                                    </select>
                                                    <button type="submit" name="submit_rating" class="btn-terima border-0 bg-secondary text-white py-1" style="font-size:0.6rem;">Kirim Rating</button>
                                                </form>
                                            <?php else: ?>
                                                <div class="text-warning small mt-1">
                                                    <?php for($i=1; $i<=$row['rating']; $i++) echo "★"; ?>
                                                    <span class="text-white opacity-50" style="font-size: 0.6rem;">(<?= $row['rating'] ?>/5)</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="opacity-50">Belum ada riwayat transaksi.</p>
                <a href="katalog.php" class="btn-terima">MULAI BELANJA</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>