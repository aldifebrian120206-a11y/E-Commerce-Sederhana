<?php
include '../config/config.php';
if (isset($_POST['daftar'])) {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Cek apakah username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
    if (mysqli_num_rows($cek) > 0) {
        $err = "Username sudah terdaftar!";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$u', '$p', 'user')");
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #4b2c20; background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); height: 100vh; display: flex; align-items: center; }
        .card-daftar { background: rgba(255,255,255,0.05); border: 2px solid #b8860b; border-radius: 20px; color: #b8860b; padding: 40px; width: 100%; max-width: 400px; margin: auto; }
        .form-control { background: rgba(255,255,255,0.1); border: 1px solid #b8860b; color: #fff; border-radius: 50px; }
        .btn-emas { background: #b8860b; color: #4b2c20; font-weight: bold; border-radius: 50px; border: none; }
    </style>
</head>
<body>
    <div class="card-daftar shadow-lg">
        <h3 class="fw-bold text-center mb-4">DAFTAR AKUN</h3>
        <?php if(isset($err)): ?><div class="alert alert-danger small py-1 text-center"><?= $err; ?></div><?php endif; ?>
        <form method="POST">
            <label class="small mb-1 ms-2 text-uppercase">Username Baru</label>
            <input type="text" name="username" class="form-control mb-3 px-4" placeholder="Contoh: batik_user" required>
            <label class="small mb-1 ms-2 text-uppercase">Password Baru</label>
            <input type="password" name="password" class="form-control mb-4 px-4" placeholder="Min. 6 Karakter" required>
            <button type="submit" name="daftar" class="btn btn-emas w-100 py-2">BUAT AKUN SEKARANG</button>
            <p class="mt-3 small text-center">Sudah punya akun? <a href="login.php" style="color: #fff;">Login di sini</a></p>
        </form>
    </div>
</body>
</html>