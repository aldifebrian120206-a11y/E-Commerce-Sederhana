<?php
session_start();
include '../config/config.php';
if (empty($_SESSION['cart'])) { header("Location: katalog.php"); exit; }

$user_sekarang = $_SESSION['login_user'];

// --- LOGIKA AMBIL NAMA USER AGAR TIDAK MUNCUL 'ADMIN' ---
$nama_default = $user_sekarang; // Cadangan jika database tidak ketemu
$cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user_sekarang'");

if ($cek_user && mysqli_num_rows($cek_user) > 0) {
    $data = mysqli_fetch_assoc($cek_user);
    // Cek apakah kolomnya bernama nama_lengkap atau nama
    if (!empty($data['nama_lengkap'])) {
        $nama_default = $data['nama_lengkap'];
    } elseif (!empty($data['nama'])) {
        $nama_default = $data['nama'];
    }
}

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
            font-family: 'Times New Roman', serif; 
        }

        .card-checkout { 
            background: var(--soga-card); 
            border: 1px solid rgba(142, 101, 22, 0.2); 
            border-radius: 15px; 
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .form-label { 
            color: var(--emas-bright); 
            font-weight: 300; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 2px;
        }

        .form-control, .form-select { 
            background: rgba(0,0,0,0.2) !important; 
            border: 1px solid rgba(142, 101, 22, 0.3) !important; 
            color: white !important; 
            font-size: 0.9rem;
            border-radius: 8px;
            padding: 10px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--emas-bright) !important;
            box-shadow: none;
        }

        .summary-box { 
            background: rgba(142, 101, 22, 0.05); 
            border: 1px solid var(--emas-dim); 
            border-radius: 12px; 
            padding: 20px; 
        }

        .payment-info {
            background: rgba(0,0,0,0.2);
            border: 1px solid rgba(142, 101, 22, 0.1);
            border-radius: 12px;
            padding: 20px;
            font-size: 0.85rem;
            color: var(--krem-soft);
        }

        .bank-name { 
            color: var(--emas-bright); 
            font-weight: bold; 
            letter-spacing: 1px;
        }

        .btn-selesai { 
            background: var(--emas-dim); 
            color: white; 
            font-weight: 300; 
            text-transform: uppercase;
            letter-spacing: 3px;
            border-radius: 8px; 
            border: none; 
            padding: 15px; 
            transition: 0.4s;
        }

        .btn-selesai:hover { 
            background: var(--emas-bright); 
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        hr { border-color: rgba(142, 101, 22, 0.2); }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5 mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-checkout p-4 p-md-5">
                    <h3 class="text-center fw-light mb-5" style="color: var(--emas-bright); letter-spacing: 5px;">CHECKOUT PEMBAYARAN</h3>
                    
                    <form action="proses_checkout.php" method="POST">
                        <div class="row g-5">
                            <div class="col-md-6 border-end border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                                <div class="mb-4">
                                    <label class="form-label">Nama Penerima</label>
                                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama_default) ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <input type="number" name="no_telp" class="form-control" placeholder="Contoh: 08123456789" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Alamat Pengiriman</label>
                                    <textarea name="alamat_lengkap" class="form-control" rows="4" placeholder="Tuliskan alamat lengkap Anda..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pilih Kurir</label>
                                    <select name="kurir" class="form-select" required>
                                        <option value="JNE">JNE Express</option>
                                        <option value="J&T">J&T Express</option>
                                        <option value="SICEPAT">SiCepat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Informasi Rekening & E-Wallet</label>
                                <div class="payment-info mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="bank-name">BCA</span>
                                        <span class="text-white">12345</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="bank-name">BRI</span>
                                        <span class="text-white">12345</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="bank-name">MANDIRI</span>
                                        <span class="text-white">123456</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="bank-name">BNI</span>
                                        <span class="text-white">12345</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="bank-name">DANA</span>
                                        <span class="text-white">087766554433</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="bank-name">SHOPEEPAY</span>
                                        <span class="text-white">087766553433</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="bank-name">OVO</span>
                                        <span class="text-white">0888998877766</span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <select name="metode_bayar" class="form-select" required>
                                        <option value="Transfer Bank">Transfer Bank (BCA/BRI/MDR/BNI)</option>
                                        <option value="E-Wallet">E-Wallet (Dana/SPay/OVO)</option>
                                    </select>
                                </div>

                                <div class="summary-box mt-4">
                                    <div class="small opacity-50 text-uppercase mb-1" style="letter-spacing: 1px;">Total Tagihan :</div>
                                    <div class="fs-2 fw-bold" style="color: var(--emas-bright); font-family: Arial, sans-serif;">
                                        Rp <?= number_format($subtotal, 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-selesai w-100 mt-5">
                            <i class="bi bi-shield-check me-2"></i> Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>