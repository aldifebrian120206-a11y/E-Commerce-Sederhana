<?php
session_start();
include '../config/config.php';

// Proteksi Login
if (!isset($_SESSION['login_user'])) {
    header("Location: ../login.php");
    exit;
}

// --- LOGIKA TAMBAH KE KERANJANG ---
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

    $_SESSION['pesan_sukses'] = "Produk berhasil ditambahkan ke keranjang!";
    
    header("Location: katalog.php");
    exit;
}

/** * PERBAIKAN QUERY: 
 * Mengambil data produk sekaligus menghitung rata-rata rating 
 * dari tabel orders yang terhubung via order_items.
 */
$query_str = "SELECT p.*, 
              AVG(o.rating) as rata_rating, 
              COUNT(o.rating) as total_ulasan
              FROM products p
              LEFT JOIN order_items oi ON p.id = oi.product_id
              LEFT JOIN orders o ON oi.order_id = o.id
              GROUP BY p.id 
              ORDER BY p.id DESC";

$query = mysqli_query($conn, $query_str);
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
            --soga-deep: #160d08; 
            --soga-card: #22140e; 
            --emas-dim: #8e6516; 
            --emas-bright: #b8924b; 
            --krem-soft: #c5b5a5; 
            --bintang: #ffc107;
        }

        body { 
            background-color: var(--soga-deep); 
            background-image: linear-gradient(rgba(22, 13, 8, 0.97), rgba(22, 13, 8, 0.97)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--krem-soft);
            font-family: 'Times New Roman', serif;
            margin: 0;
        }

        .card-produk {
            background: var(--soga-card);
            border: 1px solid rgba(142, 101, 22, 0.2);
            border-radius: 12px;
            transition: 0.4s ease;
            overflow: hidden;
            height: 100%;
        }

        .card-produk:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            border-color: var(--emas-dim);
        }

        .img-container {
            height: 250px;
            overflow: hidden;
            background: #000;
            border-bottom: 1px solid rgba(142, 101, 22, 0.1);
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            transition: 0.5s;
        }

        .card-produk:hover .img-container img {
            opacity: 1;
            transform: scale(1.05);
        }

        .card-body-custom {
            padding: 20px;
            text-align: center;
        }

        .nama-produk {
            font-weight: 300;
            font-size: 0.95rem;
            color: var(--krem-soft);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .harga-produk {
            color: var(--emas-bright);
            font-size: 1rem;
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
            letter-spacing: 1px;
        }

        /* Style Rating Bintang */
        .rating-stars {
            color: var(--bintang);
            font-size: 0.8rem;
            margin-bottom: 15px;
        }
        .text-ulasan {
            color: var(--krem-soft);
            opacity: 0.5;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        .btn-detail {
            border: 1px solid var(--emas-dim);
            color: var(--emas-bright);
            background: transparent;
            border-radius: 5px;
            padding: 6px 15px;
            font-size: 0.75rem;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .btn-detail:hover {
            background: rgba(142, 101, 22, 0.1);
            border-color: var(--emas-bright);
            color: var(--emas-bright);
        }

        .btn-beli {
            background: var(--emas-dim);
            color: #fff;
            border: none;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-beli i {
            font-size: 1.2rem;
        }

        .btn-beli:hover {
            background: var(--emas-bright);
            color: #fff;
        }

        .modal-content {
            background: var(--soga-card);
            color: var(--krem-soft);
            border: 1px solid var(--emas-dim);
            border-radius: 15px;
        }

        .modal-header { 
            border-bottom: 1px solid rgba(142, 101, 22, 0.2); 
        }
        
        .modal-title {
            color: var(--emas-bright);
            letter-spacing: 2px;
            font-size: 1.1rem;
        }

        .btn-close { 
            filter: invert(1) grayscale(100%) brightness(200%); 
        } 
        
        .alert-emas {
            background: var(--soga-card);
            border: 1px solid var(--emas-dim);
            color: var(--emas-bright);
            border-radius: 10px;
            text-align: center;
            font-size: 0.9rem;
        }

        hr {
            border-color: var(--emas-dim);
            opacity: 0.3;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-light" style="letter-spacing: 5px; color: var(--emas-bright);">KOLEKSI KATALOG</h2>
            <p class="small text-uppercase opacity-75" style="letter-spacing: 2px; color: var(--krem-soft);">Keindahan budaya dalam setiap helai kain</p>
            <div class="mx-auto mt-3" style="width: 50px; height: 1px; background: var(--emas-dim);"></div>
        </div>

        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-emas alert-dismissible fade show mb-4 shadow-sm" role="alert">
                <i class="bi bi-check2-circle me-2"></i> <?= $_SESSION['pesan_sukses']; ?>
                <a href="keranjang.php" class="ms-3 fw-bold text-decoration-none" style="color: #fff;">LIHAT KERANJANG</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <div class="row g-4">
            <?php while($p = mysqli_fetch_assoc($query)): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card-produk">
                        <div class="img-container">
                            <img src="../assets/<?= $p['foto'] ?>" alt="<?= $p['nama_produk'] ?>">
                        </div>
                        <div class="card-body-custom">
                            <h5 class="nama-produk text-truncate"><?= $p['nama_produk'] ?></h5>
                            <p class="harga-produk">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                            
                            <div class="rating-stars">
                                <?php 
                                    $rating = round($p['rata_rating']);
                                    if ($p['total_ulasan'] > 0) {
                                        for($i=1; $i<=5; $i++) {
                                            echo ($i <= $rating) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                                        }
                                        echo '<span class="text-ulasan">(' . $p['total_ulasan'] . ')</span>';
                                    } else {
                                        echo '<span class="text-ulasan opacity-25 italic text-lowercase">Belum ada ulasan</span>';
                                    }
                                ?>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <button class="btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal<?= $p['id'] ?>">
                                    DETAIL PRODUK
                                </button>
                                
                                <form action="" method="POST">
                                    <input type="hidden" name="id_produk" value="<?= $p['id'] ?>">
                                    <button type="submit" name="beli" class="btn-beli">
                                        <i class="bi bi-bag-plus"></i>
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
                                <h5 class="modal-title text-uppercase"><?= $p['nama_produk'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <img src="../assets/<?= $p['foto'] ?>" class="img-fluid rounded mb-4" style="border: 1px solid rgba(142, 101, 22, 0.2);">
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="m-0" style="color: var(--emas-bright);">Rp <?= number_format($p['harga'], 0, ',', '.') ?></h4>
                                    <span class="small text-uppercase px-2 py-1 border border-secondary text-secondary" style="font-size: 0.7rem;">Stok Tersedia</span>
                                </div>

                                <div class="mb-4">
                                    <span class="text-warning">
                                        <?php 
                                            if ($p['total_ulasan'] > 0) {
                                                for($i=1; $i<=5; $i++) {
                                                    echo ($i <= $rating) ? '★' : '☆';
                                                }
                                                echo ' <small class="text-white opacity-50">(' . number_format($p['rata_rating'], 1) . ' / 5)</small>';
                                            }
                                        ?>
                                    </span>
                                </div>

                                <h6 class="fw-bold mb-2" style="color: var(--emas-bright); font-size: 0.8rem; letter-spacing: 1px;">DESKRIPSI :</h6>
                                <p style="white-space: pre-line; color: var(--krem-soft); opacity: 0.8; font-size: 0.9rem; line-height: 1.7;">
                                    <?= !empty($p['deskripsi']) ? $p['deskripsi'] : 'Belum ada deskripsi untuk produk ini.' ?>
                                </p>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn-detail w-100 py-2" data-bs-dismiss="modal">KEMBALI KE KATALOG</button>
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