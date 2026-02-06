<?php
// Ambil data pengaturan untuk Nama Toko dan Logo
$query_settings = mysqli_query($conn, "SELECT * FROM settings WHERE id=1");
$s = mysqli_fetch_assoc($query_settings);
$nama_toko = isset($s['nama_toko']) ? $s['nama_toko'] : "Batik Nusantara";
$logo_toko = isset($s['logo_url']) ? $s['logo_url'] : "default_logo.png";
?>

<nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background: rgba(34, 20, 14, 0.95); border-bottom: 1px solid rgba(142, 101, 22, 0.3); backdrop-filter: blur(10px);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../assets/<?= $logo_toko ?>" width="40" height="40" class="rounded-circle me-2 border shadow-sm" style="border-color: #8e6516; background: #160d08; padding: 1px;" alt="Logo">
            <span class="fw-light text-uppercase d-none d-sm-inline" style="color: #b8924b; letter-spacing: 3px; font-family: 'Times New Roman', serif; font-size: 1.1rem;">
                <?= $nama_toko ?>
            </span>
        </a>

        <div class="d-flex align-items-center">
            <a href="keranjang.php" class="nav-link position-relative me-3 d-lg-none" style="color: #b8924b;">
                <i class="bi bi-cart3 fs-4"></i>
                <?php if(!empty($_SESSION['cart'])): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 0.6rem; color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                        <?= array_sum($_SESSION['cart']) ?>
                    </span>
                <?php endif; ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(142, 101, 22, 0.5);">
                <span class="navbar-toggler-icon" style="filter: invert(45%) sepia(50%) saturate(500%) hue-rotate(10deg);"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 nav-emas" href="index.php">BERANDA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 nav-emas" href="katalog.php">KATALOG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 nav-emas" href="riwayat.php">RIWAYAT</a>
                </li>
                
                <li class="nav-item ms-lg-3 d-none d-lg-block">
                    <a href="keranjang.php" class="nav-link position-relative px-2 icon-emas">
                        <i class="bi bi-cart3 fs-4" title="Keranjang Belanja"></i>
                        <?php if(!empty($_SESSION['cart'])): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm" style="font-size: 0.65rem; color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                                <?= array_sum($_SESSION['cart']) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="nav-item ms-lg-4 mt-2 mt-lg-0">
                    <a class="nav-link logout-emas" href="logout.php">
                        <i class="bi bi-box-arrow-right fs-4" title="Keluar Akun"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Link Default: Menggunakan Emas Redup (emas-dim) */
    .nav-link.nav-emas {
        color: #8e6516 !important;
        font-size: 0.85rem;
        font-weight: 300;
        letter-spacing: 2px;
        transition: all 0.4s ease;
    }

    /* Link Hover: Menjadi Emas Terang (emas-bright) */
    .nav-link.nav-emas:hover {
        color: #b8924b !important;
        text-shadow: 0 0 10px rgba(184, 146, 75, 0.3);
    }

    .icon-emas {
        color: #8e6516 !important;
        transition: 0.4s;
    }

    .icon-emas:hover {
        color: #b8924b !important;
        transform: translateY(-2px);
    }

    .logout-emas {
        color: #8e6516 !important;
        transition: 0.4s;
    }

    .logout-emas:hover {
        color: #b35e5e !important; /* Warna merah gelap selaras dengan error-msg */
        transform: scale(1.1);
    }
    
    /* Navigasi Mobile */
    @media (max-width: 991px) {
        .navbar-collapse {
            background: #22140e; /* Sama dengan soga-card */
            padding: 20px;
            border-radius: 0 0 15px 15px;
            border: 1px solid rgba(142, 101, 22, 0.2);
            border-top: none;
        }
        .nav-item {
            text-align: center;
            border-bottom: 1px solid rgba(142, 101, 22, 0.1);
            padding: 5px 0;
        }
        .nav-item:last-child {
            border-bottom: none;
        }
    }
</style>