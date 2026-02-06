<?php
session_start();
include '../config/config.php';

// Proteksi Login
if (!isset($_SESSION['login_user'])) { 
    header("Location: login.php"); // Diarahkan ke login.php dalam folder yang sama
    exit; 
}

// 1. Ambil data settings
$q_set = mysqli_query($conn, "SELECT * FROM settings WHERE id=1");
$s = mysqli_fetch_assoc($q_set);

$nama_toko = isset($s['nama_toko']) ? $s['nama_toko'] : "Batik Nusantara";
$deskripsi = isset($s['deskripsi']) ? $s['deskripsi'] : "Galeri Batik Eksklusif";
$no_wa     = isset($s['no_wa']) ? $s['no_wa'] : "";

// 2. Ambil data Produk Unggulan
$query_produk = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - <?= $nama_toko ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            margin: 0;
        }

        /* Hero Section */
        .hero-section { 
            padding: 100px 0; 
            text-align: center; 
            background: rgba(0,0,0,0.2); 
            border-bottom: 1px solid rgba(142, 101, 22, 0.2); 
            margin-bottom: 80px; 
        }

        .logo-toko {
            border: 1px solid var(--emas-dim);
            padding: 5px;
            background: var(--soga-card);
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        h1 {
            color: var(--emas-bright);
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 6px;
            font-size: 2.5rem;
        }

        /* Cards */
        .card-custom { 
            background: var(--soga-card); 
            border: 1px solid rgba(142, 101, 22, 0.2); 
            border-radius: 15px; 
            padding: 40px; 
            color: var(--krem-soft);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .card-produk { 
            border: 1px solid rgba(142, 101, 22, 0.2); 
            background: var(--soga-card); 
            border-radius: 12px; 
            overflow: hidden; 
            transition: 0.4s ease; 
            height: 100%; 
        }

        .card-produk:hover { 
            transform: translateY(-8px); 
            border-color: var(--emas-dim);
            box-shadow: 0 15px 40px rgba(0,0,0,0.5); 
        }

        .img-container { 
            height: 280px; 
            overflow: hidden; 
            background: #000;
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

        /* Buttons */
        .btn-emas { 
            background: var(--emas-dim); 
            color: #fff; 
            border: none; 
            padding: 12px 30px; 
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-emas:hover { 
            background: var(--emas-bright); 
            color: #fff;
        }

        .btn-outline-emas {
            border: 1px solid var(--emas-dim);
            color: var(--emas-bright);
            padding: 12px 30px;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-outline-emas:hover {
            border-color: var(--emas-bright);
            background: rgba(142, 101, 22, 0.1);
            color: var(--emas-bright);
        }

        .btn-wa-floating { 
            position: fixed; 
            bottom: 30px; 
            right: 30px; 
            background: var(--emas-dim); 
            color: #fff; 
            width: 55px; 
            height: 55px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 24px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.4); 
            z-index: 1000; 
            text-decoration: none; 
            transition: 0.3s;
        }

        .btn-wa-floating:hover {
            background: #25d366;
            transform: scale(1.1);
            color: #fff;
        }

        /* Typography */
        h2, h3 {
            color: var(--emas-bright);
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .text-harga {
            color: var(--emas-bright);
            font-family: 'Arial', sans-serif;
            letter-spacing: 1px;
        }

        footer {
            border-top: 1px solid rgba(142, 101, 22, 0.15);
            background: rgba(0,0,0,0.2);
            color: var(--emas-dim);
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <a href="https://api.whatsapp.com/send?phone=<?= $no_wa ?>" class="btn-wa-floating" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>

    <div class="container-fluid p-0">
        <section class="hero-section">
            <div class="container">
                <img src="../assets/<?= $s['logo_url'] ?>" class="mb-4 rounded-circle logo-toko shadow">
                <h1 class="mb-3"><?= $nama_toko ?></h1>
                <p class="mb-5 italic opacity-75">Warisan Budaya dalam Setiap Helai Kain</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#tentang-kami" class="btn-outline-emas">Filosofi</a>
                    <a href="katalog.php" class="btn-emas">Jelajahi Koleksi</a>
                </div>
            </div>
        </section>
    </div>

    <div class="container">
        <section id="tentang-kami" class="mb-5 pt-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card-custom text-center">
                        <h2 class="mb-4">Tentang Kami</h2>
                        <p class="lead italic opacity-75" style="line-height: 2; font-size: 1.1rem;">
                            <?= nl2br($deskripsi) ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card-custom h-100 text-center">
                    <h3 class="mb-3" style="font-size: 1.2rem;">Visi</h3>
                    <p class="italic opacity-75">"<?= isset($s['visi']) ? $s['visi'] : 'Visi belum diatur' ?>"</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom h-100">
                    <h3 class="mb-3 text-center" style="font-size: 1.2rem;">Misi</h3>
                    <div class="small italic opacity-75" style="line-height: 1.8;">
                        <?= isset($s['misi']) ? nl2br($s['misi']) : 'Misi belum diatur' ?>
                    </div>
                </div>
            </div>
        </div>

        <section id="produk-unggulan" class="py-5">
            <div class="text-center mb-5">
                <h2>Koleksi Terkini</h2>
                <div class="mx-auto mt-3" style="width: 50px; height: 1px; background: var(--emas-dim);"></div>
            </div>
            
            <div class="row g-4">
                <?php while($p = mysqli_fetch_assoc($query_produk)): ?>
                <div class="col-6 col-lg-3">
                    <div class="card card-produk">
                        <div class="img-container">
                            <img src="../assets/<?= $p['foto'] ?>" alt="<?= $p['nama_produk'] ?>">
                        </div>
                        <div class="card-body text-center p-4">
                            <h6 class="mb-2 text-uppercase" style="letter-spacing: 1px; font-size: 0.9rem;"><?= $p['nama_produk'] ?></h6>
                            <p class="text-harga fw-bold mb-4">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                            <a href="keranjang.php?id=<?= $p['id'] ?>" class="btn-emas w-100 py-2" style="font-size: 0.7rem;">
                                <i class="bi bi-bag me-2"></i> Periksa Produk
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-5 pt-4">
                <a href="katalog.php" class="btn-outline-emas">Lihat Seluruh Katalog <i class="bi bi-chevron-right ms-2"></i></a>
            </div>
        </section>
    </div>

    <footer class="text-center py-5 mt-5">
        <p class="small text-uppercase" style="letter-spacing: 2px;">
            &copy; <?= date('Y') ?> <?= $nama_toko ?>
        </p>
        <div class="opacity-50 small">Melestarikan Budaya, Memperindah Gaya.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>