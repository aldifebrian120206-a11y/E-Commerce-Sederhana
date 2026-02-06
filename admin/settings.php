<?php
session_start();
include '../config/config.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit; 
}

// 1. Ambil Data Settings
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
            // Hapus logo lama jika bukan default
            if($s['logo_url'] != 'default.png' && file_exists("../assets/" . $s['logo_url'])) {
                unlink("../assets/" . $s['logo_url']);
            }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Logo - Panel Admin</title>
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
            background-color: var(--soga-deep) !important; 
            background-image: linear-gradient(rgba(22, 13, 8, 0.96), rgba(22, 13, 8, 0.96)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png') !important; 
            color: var(--krem-soft) !important; 
            font-family: 'Georgia', serif; 
            min-height: 100vh;
        }

        .card-settings { 
            background: var(--soga-card) !important; 
            border: 1px solid var(--emas-dim) !important; 
            border-radius: 20px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            max-width: 550px;
            margin: auto;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi Sudut */
        .card-settings::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: var(--emas-dim);
            opacity: 0.1;
            border-radius: 50%;
        }

        .form-label { 
            color: var(--emas-bright); 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 2px;
        }

        .form-control { 
            background: rgba(0, 0, 0, 0.4) !important; 
            border: 1px solid rgba(142, 101, 22, 0.3) !important; 
            color: white !important;
            padding: 12px;
            border-radius: 10px;
        }
        
        .form-control:focus {
            border-color: var(--emas-bright) !important;
            box-shadow: 0 0 15px rgba(184, 146, 75, 0.1) !important;
        }

        .btn-simpan { 
            background: linear-gradient(135deg, var(--emas-dim), var(--emas-bright)); 
            color: var(--soga-deep) !important; 
            font-weight: bold; 
            border-radius: 10px; 
            border: none; 
            letter-spacing: 1px;
            padding: 12px;
            transition: 0.3s; 
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .btn-simpan:hover { 
            filter: brightness(1.2);
            transform: translateY(-3px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.4); 
        }

        .img-preview-container {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }

        .img-preview { 
            border: 1px solid var(--emas-dim); 
            background: #000; 
            padding: 15px; 
            border-radius: 15px; 
            width: 180px;
            height: 180px;
            object-fit: contain;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }

        .preview-label {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--emas-dim);
            color: white;
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .back-link {
            color: var(--emas-dim);
            text-decoration: none;
            font-size: 0.8rem;
            transition: 0.3s;
            letter-spacing: 1px;
        }
        .back-link:hover { color: var(--emas-bright); }

        input[type="file"]::file-selector-button {
            background: var(--soga-deep);
            color: var(--emas-bright);
            border: 1px solid var(--emas-dim);
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.7rem;
            margin-right: 15px;
        }
    </style>
</head>
<body class="py-5">
    <?php include 'navbar.php'; ?>

    <div class="container mt-lg-5">
        <div class="card-settings p-5 shadow-lg text-center">
            <div class="mb-4">
                <i class="bi bi-gear-fill" style="color: var(--emas-dim); font-size: 2rem;"></i>
                <h3 class="fw-bold mt-2" style="color: var(--emas-bright); letter-spacing: 4px;">PENGATURAN</h3>
                <p class="small opacity-50 mb-0">Sesuaikan identitas visual toko Anda</p>
            </div>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <div class="img-preview-container">
                        <img src="../assets/<?= $s['logo_url'] ?>" class="img-preview shadow">
                        <span class="preview-label text-uppercase">Logo Saat Ini</span>
                    </div>
                    
                    <div class="text-start mt-3">
                        <label class="form-label d-block text-center mb-3">Unggah Logo Baru</label>
                        <input type="file" name="logo" class="form-control" accept="image/*" required>
                        <div class="text-center mt-2">
                            <small class="opacity-50" style="font-size: 0.65rem;">Format disarankan: PNG Transparan atau JPG (Square)</small>
                        </div>
                    </div>
                </div>

                <button type="submit" name="update_logo" class="btn btn-simpan w-100 shadow mt-2">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i> Perbarui Identitas
                </button>
            </form>
            
            <div class="mt-5 pt-3 border-top" style="border-color: rgba(142, 101, 22, 0.1) !important;">
                <a href="index.php" class="back-link text-uppercase fw-bold">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>