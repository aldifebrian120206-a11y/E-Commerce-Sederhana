<?php
session_start();
include '../config/config.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); exit; 
}

// Ambil ID
if (!isset($_GET['id'])) { 
    header("Location: products.php"); // Pastikan pakai 's' jika nama file Anda products.php
    exit; 
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
$p = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$p) {
    header("Location: products.php");
    exit;
}

if (isset($_POST['edit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $hrg  = $_POST['harga']; 
    $desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $foto = $_FILES['foto']['name'];

    if ($foto != "") {
        $foto_final = time() . "_" . $foto;
        // Hapus foto lama agar tidak menumpuk (Opsional tapi disarankan)
        if(file_exists("../assets/".$p['foto'])) unlink("../assets/".$p['foto']);
        
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/" . $foto_final);
        mysqli_query($conn, "UPDATE products SET nama_produk='$nama', harga='$hrg', deskripsi='$desk', foto='$foto_final' WHERE id='$id'");
    } else {
        mysqli_query($conn, "UPDATE products SET nama_produk='$nama', harga='$hrg', deskripsi='$desk' WHERE id='$id'");
    }
    
    // REDIRECT: Pastikan nama file ini sesuai dengan file daftar produk Anda
    header("Location: products.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Batik Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --soga: #4b2c20; --emas: #b8860b; --emas-t: #ffd700; }
        body { 
            background: var(--soga); 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--emas); 
            font-family: 'Georgia', serif; 
        }
        .card-edit { background: rgba(0,0,0,0.5); border: 1px solid var(--emas); border-radius: 15px; backdrop-filter: blur(10px); }
        .form-control { background: rgba(255,255,255,0.1) !important; border: 1px solid var(--emas) !important; color: white !important; }
        .btn-update { background: var(--emas); color: var(--soga); font-weight: bold; border-radius: 50px; border: none; transition: 0.3s; }
        .btn-update:hover { background: var(--emas-t); transform: translateY(-2px); color: var(--soga); }
        .btn-batal { background: transparent; border: 1px solid #ff4444; color: #ff4444; border-radius: 50px; transition: 0.3s; text-decoration: none; display: inline-block; text-align: center; }
        .btn-batal:hover { background: #ff4444; color: white; }
    </style>
</head>
<body class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-edit p-4 shadow-lg">
                    <h4 class="text-center fw-bold mb-4" style="color: var(--emas-t)">EDIT PRODUK</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="small fw-bold">NAMA PRODUK</label>
                            <input type="text" name="nama" class="form-control" value="<?= $p['nama_produk']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">HARGA (RP)</label>
                            <input type="number" name="harga" class="form-control" value="<?= $p['harga']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold">DESKRIPSI</label>
                            <textarea name="deskripsi" class="form-control" rows="4"><?= $p['deskripsi']; ?></textarea>
                        </div>
                        <div class="mb-3 text-center">
                            <p class="small fw-bold mb-1">FOTO SAAT INI</p>
                            <img src="../assets/<?= $p['foto']; ?>" width="120" class="mb-3 rounded border border-warning shadow-sm">
                            <input type="file" name="foto" class="form-control">
                            <small class="text-white-50">*Kosongkan jika tidak ingin mengubah foto</small>
                        </div>
                        
                        <div class="row g-2 mt-4">
                            <div class="col-8">
                                <button type="submit" name="edit" class="btn btn-update w-100 py-2">SIMPAN PERUBAHAN</button>
                            </div>
                            <div class="col-4">
                                <a href="products.php" class="btn btn-batal w-100 py-2">BATAL</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>