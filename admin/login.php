<?php
session_start();
include '../config/config.php';

// Proteksi: Jika sudah login admin, langsung lempar ke index
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: index.php");
    exit;
}

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
    <title>Login Admin - Batik Nusantara</title>
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
            background-color: var(--soga-deep);
            background-image: linear-gradient(rgba(22, 13, 8, 0.9), rgba(22, 13, 8, 0.9)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Georgia', serif;
            color: var(--krem-soft);
        }

        .login-card {
            background-color: var(--soga-card);
            border: 1px solid var(--emas-dim);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        /* Aksen Batik di Pojok Card */
        .login-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png');
            opacity: 0.1;
            pointer-events: none;
        }

        .brand-icon {
            font-size: 3.5rem;
            color: var(--emas-bright);
            margin-bottom: 10px;
            filter: drop-shadow(0 0 10px rgba(184, 146, 75, 0.3));
        }

        h3 {
            color: var(--emas-bright);
            letter-spacing: 4px;
            font-weight: 300;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--emas-dim), transparent);
            width: 100%;
            margin: 20px auto;
        }

        .form-control {
            background-color: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(142, 101, 22, 0.4);
            color: white !important;
            border-radius: 8px;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .form-control:focus {
            background-color: rgba(0, 0, 0, 0.5);
            border-color: var(--emas-bright);
            box-shadow: 0 0 15px rgba(184, 146, 75, 0.2);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(197, 181, 165, 0.3);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--emas-dim), var(--emas-bright));
            color: var(--soga-deep);
            border: none;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: 0.4s;
            padding: 12px;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(184, 146, 75, 0.4);
            color: #000;
            filter: brightness(1.2);
        }

        .alert-danger {
            background: rgba(255, 0, 0, 0.05);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: #ff8080;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .footer-text {
            font-size: 0.65rem;
            color: var(--krem-soft);
            opacity: 0.4;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="login-card text-center shadow-lg">
        <i class="bi bi-shield-lock brand-icon"></i>
        <h3>ADMIN</h3>
        <p class="small text-uppercase mb-2" style="letter-spacing: 3px; color: var(--krem-soft); opacity: 0.7;">Batik Nusantara</p>
        <div class="divider"></div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger mb-4 fade show">
                <i class="bi bi-exclamation-circle me-2"></i><?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required autocomplete="off">
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="login_admin" class="btn btn-login w-100">
                Otentikasi <i class="bi bi-key-fill ms-2"></i>
            </button>
        </form>
        
        <div class="divider"></div>
        <p class="footer-text mt-4">
            Sistem Keamanan Internal &copy; 2026
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>