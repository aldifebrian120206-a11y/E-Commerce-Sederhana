<?php
session_start();
include '../config/config.php';

// --- LOGIKA PROSES KERANJANG (Di dalam satu file) ---
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Buat session keranjang jika belum ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Tambah jumlah produk ke session
    if (isset($_SESSION['cart'][$id_produk])) {
        $_SESSION['cart'][$id_produk] += 1;
    } else {
        $_SESSION['cart'][$id_produk] = 1;
    }

    // Bersihkan URL agar saat di-refresh tidak menambah jumlah terus-menerus
    header("Location: keranjang.php");
    exit;
}

// --- LOGIKA HAPUS PRODUK ---
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    unset($_SESSION['cart'][$id_hapus]);
    header("Location: keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Batik Nusantara</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --soga: #4b2c20; 
            --soga-gelap: #2c1810; 
            --emas: #b8860b; 
            --emas-t: #ffd700; 
        }

        body { 
            background: var(--soga) !important; 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png') !important; 
            color: var(--emas-t) !important; 
            font-family: 'Georgia', serif; 
            min-height: 100vh;
        }

        .card-cart { 
            background: var(--soga-gelap) !important; 
            border: 2px solid var(--emas) !important; 
            border-radius: 15px; 
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            margin-top: 20px;
        }

        /* Tabel Anti Putih */
        .table-responsive { 
            border: 1px solid var(--emas); 
            border-radius: 10px; 
            overflow: hidden; 
            background: rgba(0,0,0,0.2);
        }

        .table { 
            --bs-table-bg: transparent !important; 
            background-color: transparent !important;
            color: var(--emas-t) !important; 
            margin-bottom: 0 !important;
        }

        .table th, .table td { 
            background-color: transparent !important; 
            border-color: var(--emas) !important; 
            color: var(--emas-t) !important;
            padding: 15px !important;
            vertical-align: middle;
        }

        .table thead th { 
            background-color: var(--emas) !important; 
            color: var(--soga-gelap) !important; 
            text-transform: uppercase;
            font-weight: bold;
            border: none !important;
        }

        .btn-checkout { 
            background: var(--emas) !important; 
            color: var(--soga-gelap) !important; 
            font-weight: bold; 
            border-radius: 50px; 
            padding: 12px 40px; 
            text-decoration: none; 
            display: inline-block;
            transition: 0.3s;
            border: none;
        }

        .btn-checkout:hover { 
            background: var(--emas-t) !important; 
            box-shadow: 0 0 15px var(--emas-t);
            transform: scale(1.05);
            color: var(--soga-gelap) !important;
        }
        
        .btn-hapus { 
            color: #ff6666; 
            text-decoration: none; 
            font-size: 0.85rem; 
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-hapus:hover { color: #ff0000; text-shadow: 0 0 5px red; }

        .total-price {
            font-size: 1.8rem;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="card-cart shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0"><i class="bi bi-cart3 me-2"></i> KERANJANG BELANJA</h3>
                <a href="katalog.php" style="color: var(--emas-t); text-decoration: none;" class="small">
                    <i class="bi bi-arrow-left"></i> Kembali Belanja
                </a>
            </div>

            <?php if(empty($_SESSION['cart'])): ?>
                <div class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 4rem; opacity: 0.3;"></i>
                    <p class="mt-3" style="color: var(--emas);">Keranjang Anda masih kosong.</p>
                    <a href="katalog.php" class="btn btn-outline-warning mt-2">Mulai Belanja</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach($_SESSION['cart'] as $id => $qty): 
                                $res = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
                                $p = mysqli_fetch_assoc($res);
                                if($p):
                                    $sub = $p['harga'] * $qty;
                                    $total += $sub;
                            ?>
                            <tr>
                                <td>
                                    <span class="fw-bold text-white"><?= $p['nama_produk'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge border border-warning px-3 py-2"><?= $qty ?></span>
                                </td>
                                <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td class="fw-bold" style="color: var(--emas-t);">Rp <?= number_format($sub, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="keranjang.php?hapus=<?= $id ?>" class="btn-hapus" onclick="return confirm('Hapus barang dari keranjang?')">
                                        <i class="bi bi-trash"></i> HAPUS
                                    </a>
                                </td>
                            </tr>
                            <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 text-end">
                    <p class="mb-1 opacity-75">Total Pembayaran:</p>
                    <h2 class="total-price mb-4">Rp <?= number_format($total, 0, ',', '.') ?></h2>
                    <hr style="border-color: var(--emas); opacity: 0.3;">
                    <div class="d-flex justify-content-end align-items-center gap-4 mt-4">
                        <a href="katalog.php" style="color: var(--emas); text-decoration: none; font-weight: bold;">LANJUT BELANJA</a>
                        <a href="checkout.php" class="btn-checkout">
                            CHECKOUT SEKARANG <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>