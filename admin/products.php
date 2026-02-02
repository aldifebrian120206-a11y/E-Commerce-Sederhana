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
    <title>Kelola Koleksi - Admin Batik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { 
            --soga: #4b2c20; 
            --soga-gelap: #2c1810; 
            --emas: #b8860b; 
            --emas-t: #ffd700; 
        }

        body { 
            background: var(--soga) !important; 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png') !important; 
            color: var(--emas-t) !important; 
            font-family: 'Georgia', serif; 
        }

        /* Card Container */
        .card-custom { 
            background: var(--soga-gelap) !important; 
            border: 2px solid var(--emas) !important; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h4 { color: var(--emas-t) !important; letter-spacing: 2px; font-weight: bold; }

        /* FORM - MENGHAPUS SEMUA WARNA PUTIH */
        label { color: var(--emas-t) !important; }
        .form-control { 
            background: rgba(0,0,0,0.3) !important; 
            border: 1px solid var(--emas) !important; 
            color: var(--emas-t) !important; 
        }
        .form-control:focus { box-shadow: 0 0 10px var(--emas) !important; }

        /* TABEL - ANTI PUTIH */
        .table-responsive { 
            background: var(--soga-gelap) !important; 
            border: 1px solid var(--emas) !important; 
            border-radius: 10px;
        }

        .table { 
            --bs-table-bg: transparent !important; /* Menghapus warna default bootstrap */
            background-color: transparent !important;
            color: var(--emas-t) !important; 
            margin-bottom: 0 !important;
        }

        /* Menghapus background putih pada cell */
        .table th, .table td { 
            background-color: transparent !important; 
            border-color: var(--emas) !important; 
            color: var(--emas-t) !important;
            padding: 15px !important;
        }

        /* Gaya Khusus Header Tabel */
        .table thead th { 
            background-color: var(--emas) !important; 
            color: var(--soga-gelap) !important; 
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Baris selang-seling coklat */
        .table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.03) !important; /* Memberi sedikit perbedaan tanpa jadi putih */
        }

        /* Tombol */
        .btn-emas { 
            background: var(--emas) !important; 
            color: var(--soga-gelap) !important; 
            border: none !important;
            border-radius: 50px !important;
            font-weight: bold;
        }
        .btn-emas:hover { background: var(--emas-t) !important; }

        .btn-aksi-edit { 
            border: 1px solid var(--emas-t) !important; 
            color: var(--emas-t) !important; 
            padding: 5px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .btn-aksi-edit:hover { background: var(--emas-t) !important; color: var(--soga-gelap) !important; }

        .btn-aksi-hapus { 
            border: 1px solid #7a1a1a !important; 
            color: #ff6666 !important; 
            padding: 5px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .img-produk { 
            border: 1px solid var(--emas) !important; 
            border-radius: 8px; 
            object-fit: cover;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card-custom p-4">
                    <h4 class="text-center mb-4">TAMBAH KOLEKSI</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="small fw-bold">NAMA PRODUK</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="..." required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">HARGA (RP)</label>
                            <input type="number" name="harga" class="form-control" placeholder="..." required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">DESKRIPSI</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold">FOTO PRODUK</label>
                            <input type="file" name="foto" class="form-control" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-emas w-100 py-2">SIMPAN</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-custom p-4">
                    <h4 class="mb-4 text-uppercase">Daftar Katalog Batik</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">FOTO</th>
                                    <th>PRODUK</th>
                                    <th>HARGA</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($p = mysqli_fetch_assoc($products)): ?>
                                <tr>
                                    <td class="text-center">
                                        <img src="../assets/<?= $p['foto'] ?>" width="60" height="60" class="img-produk">
                                    </td>
                                    <td>
                                        <div style="color: var(--emas-t); font-weight: bold;"><?= $p['nama_produk'] ?></div>
                                        <div style="color: var(--emas); font-size: 0.8rem; opacity: 0.8;">
                                            <?= substr($p['deskripsi'], 0, 45) ?>...
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #fff; font-weight: bold;">Rp<?= number_format($p['harga'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn-aksi-edit">EDIT</a>
                                            <a href="products.php?hapus=<?= $p['id'] ?>" class="btn-aksi-hapus" onclick="return confirm('Hapus produk?')">HAPUS</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>