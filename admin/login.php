<?php
session_start();
include '../config/config.php';

// BARIS DI BAWAH INI DIMATIKAN SUPAYA KAMU BISA LIHAT FORM LOGIN DULU
/*
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: index.php");
    exit;
}
*/

if (isset($_POST['login_admin'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['username']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    // Query mencari role admin
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$uname' AND password='$pass' AND role='admin'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['user_id']    = $data['id'];
        $_SESSION['login_user'] = $data['username'];
        $_SESSION['role']       = 'admin';
        
        // Tetap arahkan ke index SETELAH tombol login diklik
        header("Location: index.php");
        exit;
    } else {
        $error = "Akses Ditolak! Identitas Admin Tidak Valid.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login Admin - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --coklat-soga: #4b2c20;
            --emas: #b8860b;
        }

        body {
            background-color: var(--coklat-soga);
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Georgia', serif;
        }

        .login-card {
            background-color: var(--coklat-soga);
            border: 2px solid var(--emas);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            color: var(--emas);
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--emas);
            color: white !important;
            border-radius: 50px;
            padding-left: 20px;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: #ffd700;
            box-shadow: 0 0 8px rgba(184, 134, 11, 0.5);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(184, 134, 11, 0.6);
        }

        .btn-login {
            background-color: var(--emas);
            color: var(--coklat-soga);
            border: none;
            border-radius: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background-color: #ffd700;
            color: #000;
            transform: translateY(-2px);
        }

        .divider {
            height: 2px;
            background: var(--emas);
            width: 60px;
            margin: 20px auto;
        }

        .brand-icon {
            font-size: 3rem;
            color: var(--emas);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="login-card text-center shadow-lg">
        <i class="bi bi-shield-lock brand-icon"></i>
        <h3 class="fw-bold">PANEL ADMIN </h3>
        <p class="small text-uppercase mb-2" style="letter-spacing: 3px;">Batik Nusantara</p>
        <div class="divider"></div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger bg-transparent border-danger text-danger small py-2 mb-4">
                <i class="bi bi-exclamation-triangle me-2"></i><?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username Admin" required autocomplete="off">
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="login_admin" class="btn btn-login w-100 py-2">
                Masuk <i class="bi bi-arrow-right-short"></i>
            </button>
        </form>
        
        <p class="mt-4 mb-0" style="font-size: 0.7rem; color: rgba(184, 134, 11, 0.6);">
            &copy; 2026 Batik Nusantara - Pelestarian Budaya
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>