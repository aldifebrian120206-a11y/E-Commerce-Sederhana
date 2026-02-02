<?php
// Ambil data pengaturan untuk Nama Toko dan Logo
$query_settings = mysqli_query($conn, "SELECT * FROM settings WHERE id=1");
$s = mysqli_fetch_assoc($query_settings);
$nama_toko = isset($s['nama_toko']) ? $s['nama_toko'] : "Batik Nusantara";
$logo_toko = isset($s['logo_url']) ? $s['logo_url'] : "default_logo.png";
?>

<nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background: rgba(44, 24, 16, 0.98); border-bottom: 2px solid #b8860b; backdrop-filter: blur(10px);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../assets/<?= $logo_toko ?>" width="40" height="40" class="rounded-circle me-2 border border-warning shadow" style="background: #2c1810; padding: 2px;" alt="Logo">
            <span class="fw-bold text-uppercase d-none d-sm-inline" style="color: #ffd700; letter-spacing: 1.5px; font-family: 'Georgia', serif; font-size: 1.1rem;">
                <?= $nama_toko ?>
            </span>
        </a>

        <div class="d-flex align-items-center">
            <a href="keranjang.php" class="nav-link position-relative me-3 d-lg-none" style="color: #ffd700;">
                <i class="bi bi-cart3 fs-4"></i>
                <?php if(!empty($_SESSION['cart'])): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow border border-warning" style="font-size: 0.6rem; color: #ffd700;">
                        <?= array_sum($_SESSION['cart']) ?>
                    </span>
                <?php endif; ?>
            </a>

            <button class="navbar-toggler border-warning" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(80%) sepia(50%) saturate(1000%) hue-rotate(5deg) brightness(100%);"></span>
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
                    <a href="keranjang.php" class="nav-link position-relative px-2 icon-emas" style="transition: 0.3s;">
                        <i class="bi bi-cart3 fs-4" title="Keranjang Belanja"></i>
                        <?php if(!empty($_SESSION['cart'])): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow border border-warning" style="font-size: 0.65rem; color: #ffd700;">
                                <?= array_sum($_SESSION['cart']) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <li class="nav-item ms-lg-4 mt-2 mt-lg-0">
    <a class="nav-link logout-emas" href="logout.php">
        <i class="bi bi-box-arrow-right fs-4" title="Keluar Akun"></i>
    </a>
</li>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Link Default: Emas Agak Redup */
    .nav-link.nav-emas {
        color: #b8860b !important;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Link Hover & Active: Emas Terang */
    .nav-link.nav-emas:hover, .icon-emas:hover {
        color: #ffd700 !important;
        transform: scale(1.1);
        text-shadow: 0 0 8px rgba(255, 215, 0, 0.5);
    }

    .icon-emas {
        color: #ffd700 !important;
    }

    .logout-emas {
        color: #b8860b !important;
        transition: 0.3s;
    }

    .logout-emas:hover {
        color: #ff4444 !important; /* Logout tetap merah saat hover agar kontras */
        transform: scale(1.1);
    }
    
    @media (max-width: 991px) {
        .navbar-collapse {
            background: rgba(44, 24, 16, 0.98);
            padding: 20px;
            border-radius: 10px;
            margin-top: 10px;
            border: 1px solid #b8860b;
        }
        .nav-item {
            text-align: center;
            border-bottom: 1px solid rgba(184, 134, 11, 0.2);
            padding: 10px 0;
        }
    }
</style>