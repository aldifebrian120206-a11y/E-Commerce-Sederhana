<?php
session_start();
include '../config/config.php';
if (empty($_SESSION['cart'])) { header("Location: katalog.php"); exit; }

// Hitung total belanja dari session
$subtotal = 0;
foreach($_SESSION['cart'] as $id => $qty) {
    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM products WHERE id='$id'"));
    $subtotal += ($p['harga'] * $qty);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --soga: #4b2c20; --emas: #b8860b; --emas-t: #ffd700; }
        body { 
            background: var(--soga); 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--emas); 
            font-family: 'Georgia', serif; 
        }
        .card-checkout { 
            background: rgba(0,0,0,0.8); 
            border: 2px solid var(--emas); 
            border-radius: 15px; 
            backdrop-filter: blur(10px); 
        }
        .form-label { color: var(--emas-t); font-weight: bold; font-size: 0.8rem; text-transform: uppercase; }
        .form-control, .form-select { 
            background: rgba(255,255,255,0.05) !important; 
            border: 1px solid var(--emas) !important; 
            color: white !important; 
        }
        .summary-box { 
            background: rgba(255,215,0,0.05); 
            border: 1px solid var(--emas); 
            border-radius: 10px; 
            padding: 15px; 
        }
        .payment-info {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            padding: 15px;
            font-size: 0.85rem;
            color: #fff;
        }
        .bank-name { color: var(--emas-t); font-weight: bold; }
        .btn-selesai { 
            background: var(--emas); 
            color: white; 
            font-weight: bold; 
            border-radius: 10px; 
            border: none; 
            padding: 12px; 
            transition: 0.3s;
        }
        .btn-selesai:hover { background: var(--emas-t); color: #000; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5 mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-checkout p-4 p-md-5 shadow-lg">
                    <h3 class="text-center fw-bold mb-4" style="color: var(--emas-t); letter-spacing: 2px;">CHECKOUT PEMBAYARAN</h3>
                    
                    <form action="proses_checkout.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 border-end border-secondary">
                                <div class="mb-3">
                                    <label class="form-label">Nama Penerima</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $_SESSION['login_user'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor WA</label>
                                    <input type="number" name="no_telp" class="form-control" placeholder="08xxx" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="alamat_lengkap" class="form-control" rows="4" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kurir</label>
                                    <select name="kurir" class="form-select" required>
                                        <option value="JNE">JNE</option>
                                        <option value="J&T">J&T Express</option>
                                        <option value="SICEPAT">SiCepat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4">
                                <label class="form-label">Informasi Rekening & E-Wallet</label>
                                <div class="payment-info mb-3">
                                    <p class="mb-1"><span class="bank-name">BCA:</span> 12345</p>
                                    <p class="mb-1"><span class="bank-name">BRI:</span> 12345</p>
                                    <p class="mb-1"><span class="bank-name">MANDIRI:</span> 123456</p>
                                    <p class="mb-1"><span class="bank-name">BNI:</span> 12345</p>
                                    <hr style="border-color: var(--emas);">
                                    <p class="mb-1"><span class="bank-name">DANA:</span> 087766554433</p>
                                    <p class="mb-1"><span class="bank-name">SHOPEEPAY:</span> 087766553433</p>
                                    <p class="mb-0"><span class="bank-name">OVO:</span> 0888998877766</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Konfirmasi Metode Transfer</label>
                                    <select name="metode_bayar" class="form-select" required>
                                        <option value="Transfer Bank">Transfer Bank (BCA/BRI/MDR/BNI)</option>
                                        <option value="E-Wallet">E-Wallet (Dana/SPay/OVO)</option>
                                    </select>
                                </div>

                                <div class="summary-box mt-4">
                                    <div class="small text-white">Total Tagihan:</div>
                                    <div class="fs-3 fw-bold" style="color: var(--emas-t);">Rp <?= number_format($subtotal, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-selesai w-100 mt-4 shadow fw-bold">
                            <i class="bi bi-shield-check me-2"></i> SELESAIKAN PEMBAYARAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>