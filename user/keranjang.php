<?php
session_start();
include '../config/config.php';

// --- LOGIKA PROSES KERANJANG ---
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id_produk])) {
        $_SESSION['cart'][$id_produk] += 1;
    } else {
        $_SESSION['cart'][$id_produk] = 1;
    }

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
            --soga-deep: #160d08; 
            --soga-card: #22140e; 
            --emas-dim: #8e6516; 
            --emas-bright: #b8924b; 
            --krem-soft: #c5b5a5; 
        }

        body { 
            background-color: var(--soga-deep) !important; 
            background-image: linear-gradient(rgba(22, 13, 8, 0.97), rgba(22, 13, 8, 0.97)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png') !important; 
            color: var(--krem-soft) !important; 
            font-family: 'Times New Roman', serif; 
            min-height: 100vh;
        }

        .card-cart { 
            background: var(--soga-card) !important; 
            border: 1px solid rgba(142, 101, 22, 0.2) !important; 
            border-radius: 15px; 
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            margin-top: 20px;
        }

        h3 {
            color: var(--emas-bright);
            letter-spacing: 3px;
            font-weight: 300;
            text-transform: uppercase;
        }

        /* Tabel Midnight Theme */
        .table-responsive { 
            border: 1px solid rgba(142, 101, 22, 0.1); 
            border-radius: 10px; 
            background: rgba(0,0,0,0.2);
        }

        .table { 
            --bs-table-bg: transparent !important; 
            color: var(--krem-soft) !important; 
            margin-bottom: 0 !important;
            border-color: rgba(142, 101, 22, 0.1) !important;
        }

        .table th { 
            background-color: rgba(142, 101, 22, 0.05) !important; 
            color: var(--emas-bright) !important; 
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
            padding: 20px !important;
            border-bottom: 1px solid rgba(142, 101, 22, 0.2) !important;
        }

        .table td { 
            padding: 20px !important;
            vertical-align: middle;
            border-bottom: 1px solid rgba(142, 101, 22, 0.05) !important;
        }

        .btn-checkout { 
            background: var(--emas-dim) !important; 
            color: #fff !important; 
            border-radius: 5px; 
            padding: 12px 40px; 
            text-decoration: none; 
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            transition: 0.3s;
            border: none;
        }

        .btn-checkout:hover { 
            background: var(--emas-bright) !important; 
            transform: translateY(-2px);
        }
        
        .btn-hapus { 
            color: #b35e5e; 
            text-decoration: none; 
            font-size: 0.75rem; 
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-hapus:hover { color: #ff4444; }

        .total-price {
            font-size: 2rem;
            color: var(--emas-bright);
            font-family: Arial, sans-serif;
        }

        .badge-qty {
            background: transparent;
            border: 1px solid var(--emas-dim);
            color: var(--emas-bright);
            padding: 5px 12px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="card-cart">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h3 class="m-0">Keranjang Belanja</h3>
                <a href="katalog.php" style="color: var(--emas-dim); text-decoration: none;" class="small text-uppercase">
                    <i class="bi bi-arrow-left me-1"></i> Kembali Belanja
                </a>
            </div>

            <?php if(empty($_SESSION['cart'])): ?>
                <div class="text-center py-5">
                    <i class="bi bi-cart-x opacity-25" style="font-size: 4rem;"></i>
                    <p class="mt-3 opacity-50">Keranjang Anda masih kosong.</p>
                    <a href="katalog.php" class="btn-checkout mt-3 d-inline-block">Mulai Belanja</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th>Harga</th>
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
                                    <div class="fw-normal text-white text-uppercase" style="letter-spacing: 1px; font-size: 0.9rem;"><?= $p['nama_produk'] ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge-qty"><?= $qty ?></span>
                                </td>
                                <td class="opacity-75">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td class="fw-bold" style="color: var(--emas-bright);">Rp <?= number_format($sub, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="keranjang.php?hapus=<?= $id ?>" class="btn-hapus" onclick="return confirm('Hapus barang dari keranjang?')">
                                        <i class="bi bi-trash3 me-1"></i> HAPUS
                                    </a>
                                </td>
                            </tr>
                            <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 text-end">
                    <p class="mb-1 small opacity-50 text-uppercase" style="letter-spacing: 2px;">Total Pembayaran:</p>
                    <h2 class="total-price mb-4">Rp <?= number_format($total, 0, ',', '.') ?></h2>
                    <hr style="border-color: rgba(142, 101, 22, 0.2);">
                    <div class="d-flex justify-content-end align-items-center gap-4 mt-4">
                        <a href="katalog.php" style="color: var(--emas-dim); text-decoration: none; font-size: 0.8rem;" class="text-uppercase fw-bold">Lanjut Belanja</a>
                        <a href="checkout.php" class="btn-checkout">
                            Proses Checkout <i class="bi bi-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>