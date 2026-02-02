<?php
include '../config/config.php';
$set = mysqli_fetch_assoc(mysqli_query($conn, "SELECT logo_url FROM settings WHERE id=1"));
$logo_admin = "../assets/" . $set['logo_url'];
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    :root { 
        --soga: #4b2c20; 
        --soga-gelap: #2c1810;
        --emas: #b8860b; 
        --emas-t: #ffd700; 
    }
    
    body { 
        background-color: var(--soga); 
        background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
        color: var(--emas); 
        font-family: 'Georgia', serif; 
    }

    .navbar-admin { 
        background: var(--soga-gelap) !important; 
        border-bottom: 2px solid var(--emas); 
    }
    
    .navbar-brand, .nav-link { 
        color: var(--emas) !important; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        font-weight: bold;
    }
    
    .nav-link:hover { color: var(--emas-t) !important; }
    
    .btn-logout-admin { 
        background: transparent;
        color: var(--emas) !important; 
        border: 1px solid var(--emas);
        border-radius: 50px; 
        font-weight: bold; 
        font-size: 0.8rem; 
        transition: 0.3s;
    }
    
    .btn-logout-admin:hover { 
        background: var(--emas); 
        color: var(--soga) !important; 
    }

    .badge-notif { 
        background: var(--emas-t); 
        color: var(--soga); 
        font-size: 0.7rem;
        vertical-align: middle;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin sticky-top shadow">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="<?= $logo_admin; ?>" height="35" class="me-2 rounded shadow-sm">
            <span>PANEL ADMIN</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="products.php">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 position-relative" href="orders.php">
                        Pesanan
                        <?php 
                        // PERBAIKAN: Hanya hitung pesanan yang statusnya BUKAN 'SELESAI'
                        $q_count = mysqli_query($conn, "SELECT id FROM orders WHERE status != 'SELESAI'");
                        $c = mysqli_num_rows($q_count);
                        if($c > 0): 
                        ?>
                            <span class="badge rounded-pill badge-notif ms-1"><?= $c ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-logout-admin px-4 ms-lg-3" onclick="return confirm('Keluar dari Panel Admin?')">LOGOUT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>