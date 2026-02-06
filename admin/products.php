<?php
session_start();
include '../config/config.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); exit; 
}

// --- PROSES TAMBAH PRODUK ---
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $foto = time() . "_" . $_FILES['foto']['name'];
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/" . $foto)) {
        mysqli_query($conn, "INSERT INTO products (nama_produk, harga, deskripsi, foto) VALUES ('$nama', '$harga', '$desk', '$foto')");
        header("Location: products.php");
        exit;
    }
}

// --- PROSES HAPUS ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $get_foto = mysqli_query($conn, "SELECT foto FROM products WHERE id='$id'");
    $f = mysqli_fetch_assoc($get_foto);
    if(file_exists("../assets/".$f['foto'])) unlink("../assets/".$f['foto']);

    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: products.php");
    exit;
}

$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Koleksi - Admin Batik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background-image: linear-gradient(rgba(22, 13, 8, 0.95), rgba(22, 13, 8, 0.95)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png') !important; 
            color: var(--krem-soft) !important; 
            font-family: 'Georgia', serif; 
        }

        /* Card Custom */
        .card-custom { 
            background: var(--soga-card) !important; 
            border: 1px solid var(--emas-dim) !important; 
            border-radius: 15px; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            position: relative;
            overflow: hidden;
        }

        h4 { color: var(--emas-bright) !important; letter-spacing: 3px; font-weight: bold; text-transform: uppercase; }

        /* Form Styling - Anti Putih */
        label { color: var(--emas-bright) !important; letter-spacing: 1px; margin-bottom: 5px; }
        .form-control { 
            background: rgba(0,0,0,0.4) !important; 
            border: 1px solid rgba(142, 101, 22, 0.3) !important; 
            color: white !important; 
            border-radius: 8px;
        }
        .form-control:focus { 
            background: rgba(0,0,0,0.6) !important;
            border-color: var(--emas-bright) !important; 
            box-shadow: 0 0 10px rgba(184, 146, 75, 0.2) !important; 
        }
        .form-control::placeholder { color: rgba(197, 181, 165, 0.2); }

        /* File Input khusus */
        input[type="file"]::file-selector-button {
            background: var(--emas-dim);
            color: var(--soga-deep);
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            margin-right: 10px;
            cursor: pointer;
        }

        /* Tabel Styling */
        .table-responsive { 
            border: 1px solid var(--emas-dim) !important; 
            border-radius: 10px;
            background: transparent;
        }

        .table { 
            color: var(--krem-soft) !important; 
            margin-bottom: 0 !important;
        }

        .table thead th { 
            background-color: var(--emas-dim) !important; 
            color: var(--soga-deep) !important; 
            border: none !important;
            padding: 15px !important;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .table td { 
            background-color: transparent !important; 
            border-color: rgba(142, 101, 22, 0.1) !important; 
            padding: 15px !important;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: rgba(184, 146, 75, 0.03) !important;
        }

        /* Tombol Utama */
        .btn-emas { 
            background: linear-gradient(135deg, var(--emas-dim), var(--emas-bright)) !important; 
            color: var(--soga-deep) !important; 
            border: none !important;
            border-radius: 8px !important;
            font-weight: bold;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-emas:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(184, 146, 75, 0.3);
            filter: brightness(1.1);
        }

        /* Tombol Aksi */
        .btn-aksi-edit { 
            border: 1px solid var(--emas-bright) !important; 
            color: var(--emas-bright) !important; 
            padding: 6px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-aksi-edit:hover { background: var(--emas-bright) !important; color: var(--soga-deep) !important; }

        .btn-aksi-hapus { 
            border: 1px solid #7a1a1a !important; 
            color: #ff8080 !important; 
            padding: 6px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-aksi-hapus:hover { background: #7a1a1a !important; color: white !important; }

        .img-produk { 
            border: 1px solid var(--emas-dim) !important; 
            border-radius: 8px; 
            object-fit: cover;
            box-shadow: 0 5px 10px rgba(0,0,0,0.3);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--soga-deep); }
        ::-webkit-scrollbar-thumb { background: var(--emas-dim); border-radius: 10px; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card-custom p-4 sticky-top" style="top: 100px; z-index: 10;">
                    <h4 class="text-center mb-4">TAMBAH KOLEKSI</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="small fw-bold">NAMA PRODUK</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Masukkan nama batik..." required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">HARGA (RP)</label>
                            <input type="number" name="harga" class="form-control" placeholder="Contoh: 250000" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">DESKRIPSI</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Detail produk..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold">FOTO PRODUK</label>
                            <input type="file" name="foto" class="form-control" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-emas w-100 py-2">
                            SIMPAN KOLEKSI <i class="bi bi-plus-lg ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-custom p-4">
                    <h4 class="mb-4">DAFTAR KATALOG BATIK</h4>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" width="100">FOTO</th>
                                    <th>PRODUK</th>
                                    <th width="150">HARGA</th>
                                    <th class="text-center" width="180">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($products) > 0): ?>
                                    <?php while($p = mysqli_fetch_assoc($products)): ?>
                                    <tr>
                                        <td class="text-center">
                                            <img src="../assets/<?= $p['foto'] ?>" width="70" height="70" class="img-produk">
                                        </td>
                                        <td>
                                            <div style="color: var(--emas-bright); font-weight: bold; letter-spacing: 0.5px;"><?= strtoupper($p['nama_produk']) ?></div>
                                            <div style="color: var(--krem-soft); font-size: 0.75rem; opacity: 0.6; margin-top: 4px; line-height: 1.4;">
                                                <?= (strlen($p['deskripsi']) > 60) ? substr($p['deskripsi'], 0, 60) . '...' : $p['deskripsi'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="color: #fff; font-family: 'Arial', sans-serif; font-size: 0.9rem;">
                                                Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="edit.php?id=<?= $p['id'] ?>" class="btn-aksi-edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="products.php?hapus=<?= $p['id'] ?>" class="btn-aksi-hapus" onclick="return confirm('Hapus produk ini secara permanen?')">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center opacity-50 py-5">Belum ada koleksi produk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>