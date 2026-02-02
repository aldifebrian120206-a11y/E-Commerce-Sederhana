<?php
session_start();
include '../config/config.php';

// Proteksi Login
if (!isset($_SESSION['login_user'])) {
    header("Location: ../login.php");
    exit;
}

// --- LOGIKA TAMBAH KE KERANJANG (TANPA PINDAH HALAMAN) ---
if (isset($_POST['beli'])) {
    $id_produk = $_POST['id_produk'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id_produk])) {
        $_SESSION['cart'][$id_produk] += 1;
    } else {
        $_SESSION['cart'][$id_produk] = 1;
    }

    // Set notifikasi sukses
    $_SESSION['pesan_sukses'] = "Produk berhasil ditambahkan ke keranjang!";
    
    header("Location: katalog.php");
    exit;
}

// Ambil data produk
$query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Batik Nusantara</title>
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
            background: var(--soga); 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--emas-t); 
            font-family: 'Georgia', serif; 
        }

        .card-produk {
            background: var(--soga-gelap);
            border: 2px solid var(--emas);
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            height: 100%;
        }

        .card-produk:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.8);
            border-color: var(--emas-t);
        }

        .img-container {
            height: 250px;
            overflow: hidden;
            border-bottom: 2px solid var(--emas);
            background: #000;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }

        .card-body-custom {
            padding: 20px;
            text-align: center;
        }

        .nama-produk {
            font-weight: bold;
            font-size: 1.1rem;
            color: var(--emas-t);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .harga-produk {
            color: var(--emas);
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .btn-detail {
            border: 1px solid var(--emas);
            color: var(--emas-t);
            background: transparent;
            border-radius: 50px;
            padding: 5px 15px;
            font-size: 0.8rem;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-detail:hover {
            background: var(--emas);
            color: var(--soga-gelap);
        }

        .btn-beli {
            background: var(--emas);
            color: var(--soga-gelap);
            border: none;
            border-radius: 50px;
            width: 100%;
            padding: 10px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-beli i {
            font-size: 1.4rem; /* Ukuran logo keranjang dibuat lebih besar karena tanpa teks */
        }

        .btn-beli:hover {
            background: var(--emas-t);
            box-shadow: 0 0 15px var(--emas-t);
            transform: scale(1.05);
        }

        /* Modal Anti Putih */
        .modal-content {
            background: var(--soga-gelap);
            color: var(--emas-t);
            border: 2px solid var(--emas);
            border-radius: 20px;
        }

        .modal-header { border-bottom: 1px solid rgba(184, 134, 11, 0.3); }
        .btn-close { filter: invert(1) sepia(1) saturate(5) hue-rotate(10deg); } 
        
        /* Alert Custom */
        .alert-emas {
            background: var(--soga-gelap);
            border: 1px solid var(--emas-t);
            color: var(--emas-t);
            border-radius: 50px;
            text-align: center;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="letter-spacing: 3px; color: var(--emas-t);">KOLEKSI BATIK KAMI</h2>
            <p style="color: var(--emas);">Keindahan seni budaya dalam balutan kain terbaik</p>
            <hr style="width: 60px; border: 2px solid var(--emas-t); margin: auto; opacity: 1;">
        </div>

        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-emas alert-dismissible fade show mb-4 shadow" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['pesan_sukses']; ?>
                <a href="keranjang.php" class="ms-3 fw-bold text-decoration-underline" style="color: var(--emas-t);">Lihat Keranjang</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <div class="row g-4">
            <?php while($p = mysqli_fetch_assoc($query)): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card-produk shadow">
                        <div class="img-container">
                            <img src="../assets/<?= $p['foto'] ?>" alt="<?= $p['nama_produk'] ?>">
                        </div>
                        <div class="card-body-custom">
                            <h5 class="nama-produk text-truncate"><?= $p['nama_produk'] ?></h5>
                            <p class="harga-produk">Rp<?= number_format($p['harga'], 0, ',', '.') ?></p>
                            
                            <div class="d-flex flex-column gap-2">
                                <button class="btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal<?= $p['id'] ?>">
                                    <i class="bi bi-eye me-1"></i> DETAIL
                                </button>
                                
                                <form action="" method="POST">
                                    <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
                                    <button type="submit" name="beli" class="btn-beli">
                                        <i class="bi bi-cart-plus-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="detailModal<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content shadow-lg">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold text-uppercase" style="color: var(--emas-t);"><?= $p['nama_produk'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <img src="../assets/<?= $p['foto'] ?>" class="img-fluid rounded mb-4 border border-warning" style="background: #000;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="m-0" style="color: var(--emas-t);">Rp<?= number_format($p['harga'], 0, ',', '.') ?></h4>
                                    <span class="badge border border-warning text-warning">Stok Tersedia</span>
                                </div>
                                <h6 class="fw-bold" style="color: var(--emas);">DESKRIPSI:</h6>
                                <p style="white-space: pre-line; color: var(--emas); opacity: 0.9; font-size: 0.95rem; line-height: 1.6;">
                                    <?= !empty($p['deskripsi']) ? $p['deskripsi'] : 'Belum ada deskripsi untuk produk ini.' ?>
                                </p>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-warning w-100 rounded-pill" data-bs-dismiss="modal">TUTUP</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>