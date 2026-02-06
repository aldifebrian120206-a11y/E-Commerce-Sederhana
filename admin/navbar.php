<?php
include '../config/config.php';
$set = mysqli_fetch_assoc(mysqli_query($conn, "SELECT logo_url FROM settings WHERE id=1"));
$logo_admin = "../assets/" . $set['logo_url'];
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    :root { 
        --soga-deep: #160d08; 
        --soga-card: #22140e; 
        --emas-dim: #8e6516; 
        --emas-bright: #b8924b; 
        --krem-soft: #c5b5a5; 
    }
    
    /* Navbar Admin Styling */
    .navbar-admin { 
        background: var(--soga-card) !important; 
        border-bottom: 1px solid var(--emas-dim);
        padding: 12px 0;
    }
    
    .navbar-brand {
        font-family: 'Georgia', serif;
        color: var(--emas-bright) !important; 
        text-transform: uppercase; 
        letter-spacing: 3px; 
        font-weight: 300;
        font-size: 1.1rem;
    }

    .navbar-brand img {
        border: 1px solid var(--emas-dim);
        padding: 2px;
        background: rgba(142, 101, 22, 0.1);
    }
    
    .nav-link { 
        color: var(--krem-soft) !important; 
        text-transform: uppercase; 
        letter-spacing: 2px; 
        font-size: 0.75rem;
        transition: 0.3s;
        opacity: 0.8;
    }
    
    .nav-link:hover { 
        color: var(--emas-bright) !important; 
        opacity: 1;
        transform: translateY(-1px);
    }

    /* Link Aktif (Opsional jika ingin ditambahkan logic active) */
    .nav-link.active {
        color: var(--emas-bright) !important;
        border-bottom: 1px solid var(--emas-bright);
    }
    
    .btn-logout-admin { 
        background: transparent;
        color: #ff8080 !important; 
        border: 1px solid rgba(255, 128, 128, 0.3);
        border-radius: 5px; 
        font-weight: bold; 
        font-size: 0.7rem; 
        letter-spacing: 1px;
        transition: 0.3s;
        text-transform: uppercase;
    }
    
    .btn-logout-admin:hover { 
        background: rgba(255, 128, 128, 0.1); 
        border-color: #ff8080;
        color: #ff8080 !important; 
    }

    .badge-notif { 
        background: var(--emas-bright) !important; 
        color: var(--soga-deep) !important; 
        font-size: 0.6rem;
        font-weight: bold;
        padding: 4px 6px;
    }

    /* Garis bawah animasi saat hover */
    .nav-item::after {
        content: '';
        display: block;
        width: 0;
        height: 1px;
        background: var(--emas-bright);
        transition: width .3s;
    }
    .nav-item:hover::after {
        width: 100%;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin sticky-top shadow-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="<?= $logo_admin; ?>" height="38" class="me-3 rounded shadow-sm">
            <span>PANEL ADMIN</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="bi bi-list" style="color: var(--emas-bright); font-size: 1.5rem;"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item mx-lg-2">
                    <a class="nav-link px-3" href="index.php">Beranda</a>
                </li>
                <li class="nav-item mx-lg-2">
                    <a class="nav-link px-3" href="products.php">Produk</a>
                </li>
                <li class="nav-item mx-lg-2">
                    <a class="nav-link px-3 position-relative" href="orders.php">
                        Pesanan
                        <?php 
                        $q_count = mysqli_query($conn, "SELECT id FROM orders WHERE status != 'SELESAI'");
                        $c = mysqli_num_rows($q_count);
                        if($c > 0): 
                        ?>
                            <span class="badge rounded-pill badge-notif ms-1"><?= $c ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item mx-lg-2">
                    <a class="nav-link px-3" href="settings.php">Settings</a>
                </li>
                <li class="nav-item ms-lg-4">
                    <a href="logout.php" class="btn btn-logout-admin px-4" onclick="return confirm('Keluar dari Panel Admin?')">
                        <i class="bi bi-power me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>