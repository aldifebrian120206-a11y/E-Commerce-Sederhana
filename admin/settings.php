<?php
session_start();
include '../config/config.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit; 
}

// 1. Ambil Data Settings (Hanya ambil logo_url)
$query_settings = mysqli_query($conn, "SELECT logo_url FROM settings WHERE id=1");
if (mysqli_num_rows($query_settings) == 0) {
    mysqli_query($conn, "INSERT INTO settings (id, logo_url) VALUES (1, 'default.png')");
    $query_settings = mysqli_query($conn, "SELECT logo_url FROM settings WHERE id=1");
}
$s = mysqli_fetch_assoc($query_settings);

// 2. Proses Update Logo
if (isset($_POST['update_logo'])) {
    if (!empty($_FILES['logo']['name'])) {
        $logo_baru = time() . "_" . $_FILES['logo']['name'];
        if(move_uploaded_file($_FILES['logo']['tmp_name'], "../assets/" . $logo_baru)) {
            mysqli_query($conn, "UPDATE settings SET logo_url='$logo_baru' WHERE id=1");
            echo "<script>alert('Logo Berhasil Diperbarui!'); window.location='settings.php';</script>";
        } else {
            echo "<script>alert('Gagal mengunggah file.');</script>";
        }
    } else {
        echo "<script>alert('Pilih file logo terlebih dahulu!');</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Settings Logo - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --soga: #4b2c20; --soga-g: #2c1810; --emas: #b8860b; --emas-t: #ffd700; }
        body { 
            background: var(--soga); 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--emas); 
            font-family: 'Georgia', serif; 
        }
        .card-settings { 
            background: rgba(0, 0, 0, 0.6); 
            border: 2px solid var(--emas); 
            border-radius: 15px; 
            backdrop-filter: blur(10px); 
            max-width: 500px;
            margin: auto;
        }
        .form-label { color: var(--emas-t); font-weight: bold; text-transform: uppercase; font-size: 0.9rem; }
        .form-control { background: rgba(255, 255, 255, 0.05) !important; border: 1px solid var(--emas) !important; color: white !important; }
        .btn-simpan { 
            background: var(--emas); 
            color: var(--soga); 
            font-weight: bold; 
            border-radius: 50px; 
            border: none; 
            transition: 0.3s; 
        }
        .btn-simpan:hover { background: var(--emas-t); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255,215,0,0.3); }
        .img-preview { 
            border: 2px solid var(--emas); 
            background: var(--soga-g); 
            padding: 10px; 
            border-radius: 15px; 
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body class="py-5">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="card-settings p-5 shadow-lg text-center">
            <h3 class="fw-bold mb-4" style="color: var(--emas-t);">LOGO TOKO</h3>
            <p class="small text-warning mb-4">Logo ini akan muncul di Beranda dan Navbar</p>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <img src="../assets/<?= $s['logo_url'] ?>" class="img-preview mb-4 shadow">
                    <label class="form-label d-block mb-2 text-start text-center">Pilih File Logo Baru</label>
                    <input type="file" name="logo" class="form-control" accept="image/*" required>
                </div>

                <button type="submit" name="update_logo" class="btn btn-simpan w-100 py-2 shadow">
                    GANTI LOGO SEKARANG
                </button>
            </form>
            
            <div class="mt-4">
                <a href="index.php" style="color: var(--emas); text-decoration: none; font-size: 0.8rem;">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>