<?php
session_start();
include '../config/config.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); exit; 
}

// --- PROSES SIMPAN PRODUK ---
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Penanganan upload foto
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_baru = time() . "_" . $foto_name;
    
    $path = "../assets/" . $foto_baru;

    if (move_uploaded_file($foto_tmp, $path)) {
        $query = "INSERT INTO products (nama_produk, harga, deskripsi, foto) 
                  VALUES ('$nama', '$harga', '$desk', '$foto_baru')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: products.php?status=sukses");
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal upload gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Koleksi Baru - Admin Batik</title>
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
            color: var(--emas-t); 
            font-family: 'Georgia', serif; 
        }

        .card-tambah { 
            background: rgba(0, 0, 0, 0.6); 
            border: 2px solid var(--emas); 
            border-radius: 20px; 
            backdrop-filter: blur(10px); 
            padding: 40px;
        }

        .form-label { font-weight: bold; letter-spacing: 1px; color: var(--emas-t); }

        .form-control { 
            background: rgba(255, 255, 255, 0.05) !important; 
            border: 1px solid var(--emas) !important; 
            color: white !important;
            border-radius: 8px;
        }

        .form-control:focus { 
            box-shadow: 0 0 15px var(--emas); 
            border-color: var(--emas-t) !important; 
        }

        .btn-simpan { 
            background: var(--emas); 
            color: var(--soga-gelap); 
            font-weight: bold; 
            border: none; 
            border-radius: 50px;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-simpan:hover { 
            background: var(--emas-t); 
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(255, 215, 0, 0.4);
        }

        .btn-batal { color: var(--emas); text-decoration: none; font-weight: bold; }
        .btn-batal:hover { color: white; }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="letter-spacing: 3px;">TAMBAH KOLEKSI BATIK</h2>
                    <hr style="width: 80px; border: 2px solid var(--emas-t); margin: auto; opacity: 1;">
                </div>

                <div class="card-tambah shadow-lg">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Nama Produk Batik</label>
                            <input type="text" name="nama_produk" class="form-control form-control-lg" placeholder="Masukkan nama batik..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control form-control-lg" placeholder="Harga tanpa titik (Contoh: 250000)" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="6" placeholder="Ceritakan filosofi atau detail kain batik ini secara lengkap..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-uppercase">Unggah Foto Produk</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                            <small class="text-white-50 mt-1 d-block italic">*Gunakan foto berkualitas tinggi agar menarik pembeli.</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="simpan" class="btn btn-simpan mb-2">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>SIMPAN KE KATALOG
                            </button>
                            <a href="products.php" class="btn btn-batal text-center">BATAL DAN KEMBALI</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>