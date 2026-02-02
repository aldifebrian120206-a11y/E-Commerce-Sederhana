<?php
session_start();
include '../config/config.php';

// Proteksi: Jika sudah login, dilempar ke index.
if (isset($_SESSION['login_user'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password === $row['password']) {
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: ../admin/orders.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Kata sandi salah.";
        }
    } else {
        $error = "Akun tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --soga: #2c1810; 
            --soga-m: #3d251c; 
            --emas: #b8860b; 
            --emas-t: #ffd700; 
            --krem: #f5deb3; 
        }

        body { 
            background: var(--soga); 
            background-image: url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--krem);
            font-family: 'Georgia', serif; 
            display: flex;
            align-items: center;      
            justify-content: center;   
            min-height: 100vh;
            margin: 0;
        }

        .wrapper {
            position: relative;
            padding: 20px 60px;
        }

        /* Aksen Batik Samping Melengkung */
        .wrapper::before, .wrapper::after {
            content: "";
            position: absolute;
            width: 8px;
            height: 50%;
            top: 25%;
            background: linear-gradient(to bottom, transparent, var(--emas), transparent);
            border-radius: 50px;
            opacity: 0.4;
            box-shadow: 0 0 15px var(--emas);
        }
        .wrapper::before { left: 10px; }
        .wrapper::after { right: 10px; }

        .login-card {
            background: var(--soga-m);
            border: 2px solid var(--emas); 
            border-radius: 50px; 
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.8);
            text-align: center;
        }

        .profile-siluet {
            width: 85px;
            height: 85px;
            margin: 0 auto 25px;
            border: 2px solid var(--emas);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--soga);
            color: var(--emas-t);
            box-shadow: 0 0 15px rgba(184, 134, 11, 0.3);
        }
        .profile-siluet i { font-size: 3.2rem; }

        h2 {
            color: var(--emas-t);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 30px;
            font-size: 1.6rem;
        }

        .form-label {
            color: var(--emas);
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            text-align: left;
            margin-left: 20px;
            margin-bottom: 5px;
        }

        .form-control {
            background: var(--soga) !important;
            border: 1px solid var(--emas);
            color: var(--krem) !important;
            border-radius: 25px; 
            padding: 12px 25px;
            margin-bottom: 20px;
        }

        .btn-login {
            background: var(--emas);
            color: var(--soga);
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 25px; 
            font-weight: bold;
            text-transform: uppercase;
            transition: 0.3s;
            letter-spacing: 1px;
        }

        .btn-login:hover {
            background: var(--emas-t);
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        /* Bagian Footer untuk Register */
        .footer-text {
            margin-top: 30px;
            font-size: 0.9rem;
            color: var(--krem);
            opacity: 0.8;
            border-top: 1px solid rgba(184, 134, 11, 0.3);
            padding-top: 20px;
        }

        .footer-text a {
            color: var(--emas-t);
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .footer-text a:hover {
            text-shadow: 0 0 10px var(--emas-t);
        }

        .error-msg {
            color: #ff4d4d;
            font-size: 0.85rem;
            margin-bottom: 20px;
            background: rgba(255, 77, 77, 0.1);
            padding: 8px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="login-card">
            <div class="profile-siluet">
                <i class="bi bi-person-circle"></i>
            </div>
            
            <h2>Pintu Masuk</h2>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i class="bi bi-exclamation-triangle me-1"></i> <?= $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="ID Pengguna..." required autocomplete="off">
                
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Kata Sandi..." required>

                <button type="submit" name="login" class="btn-login">Masuk Sekarang</button>
            </form>

            <div class="footer-text">
                Belum memiliki akun? <br>
                <a href="../register.php">Daftar Akun Baru</a>
            </div>
        </div>
    </div>

</body>
</html>