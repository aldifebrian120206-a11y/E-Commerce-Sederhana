<?php
session_start();
include '../config/config.php';

// Proteksi Login
if (!isset($_SESSION['login_user'])) { 
    header("Location: ../login.php"); 
    exit; 
}

// 1. Ambil data settings
$q_set = mysqli_query($conn, "SELECT * FROM settings WHERE id=1");
$s = mysqli_fetch_assoc($q_set);

// Variabel Dinamis
$nama_toko = isset($s['nama_toko']) ? $s['nama_toko'] : "Batik Nusantara";
$deskripsi = isset($s['deskripsi']) ? $s['deskripsi'] : "Galeri Batik Eksklusif";
$no_wa     = isset($s['no_wa']) ? $s['no_wa'] : "";

// 2. Ambil data Produk Unggulan (4 produk terbaru)
$query_produk = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - <?= $nama_toko ?></title>
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
            color: var(--emas-t); /* Font default jadi emas terang */
            font-family: 'Georgia', serif; 
        }

        /* Layouting */
        .hero-section { 
            padding: 80px 0; 
            text-align: center; 
            background: rgba(0,0,0,0.4); 
            border-radius: 0 0 50px 50px; 
            border-bottom: 2px solid var(--emas); 
            margin-bottom: 60px; 
        }

        .card-custom { 
            background: rgba(0,0,0,0.3); 
            border: 1px solid var(--emas); 
            border-radius: 20px; 
            backdrop-filter: blur(8px); 
            padding: 40px; 
            transition: 0.3s; 
            color: var(--emas-t);
        }
        
        /* Product Card Styling */
        .card-produk { 
            border: 1px solid var(--emas); 
            background: var(--soga-gelap); 
            border-radius: 15px; 
            overflow: hidden; 
            transition: 0.4s; 
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
            border-bottom: 1px solid var(--emas); 
        }

        .img-container img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: 0.5s; 
        }
        
        /* UI Elements */
        .btn-emas { 
            background: var(--emas); 
            color: var(--soga-gelap); 
            font-weight: bold; 
            border-radius: 50px; 
            border: none; 
            padding: 10px 25px; 
            transition: 0.3s;
        }

        .btn-emas:hover { 
            background: var(--emas-t); 
            color: var(--soga-gelap); 
            box-shadow: 0 0 15px var(--emas-t);
        }

        .btn-outline-emas {
            border: 1px solid var(--emas-t);
            color: var(--emas-t);
            border-radius: 50px;
            padding: 10px 25px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-emas:hover {
            background: var(--emas);
            color: var(--soga-gelap);
            border-color: var(--emas);
        }

        .btn-wa-floating { 
            position: fixed; 
            bottom: 30px; 
            right: 30px; 
            background: #25d366; 
            color: var(--soga-gelap); 
            width: 60px; 
            height: 60px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 30px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.5); 
            z-index: 1000; 
            text-decoration: none; 
        }

        .logo-toko {
            border: 3px solid var(--emas-t);
            background: var(--soga-gelap);
            padding: 5px;
        }

        /* Override bootstrap text colors */
        .text-white { color: var(--emas-t) !important; }
        .opacity-75 { opacity: 0.85; color: var(--emas-t); }
        .italic { font-style: italic; }
        hr { border-color: var(--emas-t); opacity: 0.5; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <a href="https://api.whatsapp.com/send?phone=<?= $no_wa ?>" class="btn-wa-floating shadow" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>

    <div class="container py-5">
        <section class="hero-section shadow-lg">
            <img src="../assets/<?= $s['logo_url'] ?>" height="110" class="mb-4 rounded-circle logo-toko shadow-lg">
            <h1 class="display-4 fw-bold" style="color: var(--emas-t);"><?= $nama_toko ?></h1>
            <div class="mt-4">
                <a href="#tentang-kami" class="btn-outline-emas me-2">Tentang Kami</a>
                <a href="katalog.php" class="btn-emas">Belanja Sekarang</a>
            </div>
        </section>

        <section id="tentang-kami" class="mb-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card-custom text-center shadow">
                        <h2 class="fw-bold text-uppercase mb-4" style="letter-spacing: 3px; color: var(--emas-t);">Tentang Kami</h2>
                        <p class="lead italic" style="line-height: 1.8; color: var(--emas-t);">
                            <?= nl2br($deskripsi) ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card-custom h-100 shadow-sm text-center">
                    <h3 class="fw-bold text-uppercase mb-4" style="letter-spacing: 3px; color: var(--emas-t);">Visi Kami</h3>
                    <p class="fs-5 italic" style="line-height: 1.6; color: var(--emas-t);">
                        "<?= isset($s['visi']) ? $s['visi'] : 'Visi belum diatur' ?>"
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-custom h-100 shadow-sm">
                    <h3 class="fw-bold text-uppercase text-center mb-4" style="letter-spacing: 3px; color: var(--emas-t);">Misi Kami</h3>
                    <div style="line-height: 1.8; color: var(--emas-t);">
                        <?= isset($s['misi']) ? nl2br($s['misi']) : 'Misi belum diatur' ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4"></div>

        <section id="produk-unggulan" class="mb-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-uppercase" style="letter-spacing: 4px; color: var(--emas-t);">Koleksi Unggulan</h2>
                <div class="mx-auto mt-2" style="width: 80px; height: 3px; background: var(--emas-t);"></div>
            </div>
            
            <div class="row g-4">
                <?php while($p = mysqli_fetch_assoc($query_produk)): ?>
                <div class="col-6 col-md-3">
                    <div class="card card-produk shadow">
                        <div class="img-container">
                            <img src="../assets/<?= $p['foto'] ?>" alt="<?= $p['nama_produk'] ?>">
                        </div>
                        <div class="card-body text-center d-flex flex-column">
                            <h6 class="fw-bold mb-2" style="color: var(--emas-t);"><?= $p['nama_produk'] ?></h6>
                            <p class="fw-bold mb-3" style="color: var(--emas);">Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                            <a href="keranjang.php?id=<?= $p['id'] ?>" class="btn-emas btn-sm mt-auto shadow-sm">
                                <i class="bi bi-cart-plus me-1"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="text-center mt-5">
                <a href="katalog.php" class="btn-outline-emas px-5">Lihat Koleksi Lainnya <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </section>
    </div>

    <footer class="text-center py-5 mt-5 border-top border-warning">
        <p class="small" style="color: var(--emas);">&copy; <?= date('Y') ?> <?= $nama_toko ?> | Melestarikan Budaya, Memperindah Gaya.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>