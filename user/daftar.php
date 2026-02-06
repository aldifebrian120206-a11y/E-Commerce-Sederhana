<?php
include '../config/config.php';

$err = "";

if (isset($_POST['daftar'])) {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = mysqli_real_escape_string($conn, $_POST['password']);
    
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
    if (mysqli_num_rows($cek) > 0) {
        $err = "Username sudah terdaftar!";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$u', '$p', 'user')");
        // Pastikan arahnya ke login.php (tanpa ../ karena satu folder)
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Batik Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            background-image: linear-gradient(rgba(22, 13, 8, 0.97), rgba(22, 13, 8, 0.97)), 
                              url('https://www.transparenttextures.com/patterns/batik-thin.png'); 
            color: var(--krem-soft);
            font-family: 'Times New Roman', serif;
            display: flex;
            align-items: center;      
            justify-content: center;   
            min-height: 100vh;
            margin: 0;
        }

        .card-daftar {
            background: var(--soga-card);
            border: 1px solid rgba(142, 101, 22, 0.3);
            border-radius: 20px;
            padding: 50px 45px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
            text-align: center;
        }

        .profile-siluet {
            width: 65px;
            height: 65px;
            margin: 0 auto 20px;
            border: 1px solid var(--emas-dim);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--emas-bright);
        }
        .profile-siluet i { font-size: 2.2rem; }

        h2 {
            color: var(--emas-bright);
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 35px;
            font-size: 1.3rem;
        }

        .form-label {
            color: var(--emas-dim);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: block;
            text-align: left;
            margin-bottom: 8px;
            padding-left: 2px;
        }

        .form-control {
            background: rgba(0,0,0,0.15) !important;
            border: 1px solid #36261b;
            color: var(--krem-soft) !important;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(0,0,0,0.25) !important;
            border-color: var(--emas-dim);
            box-shadow: none;
        }

        .btn-emas {
            background: var(--emas-dim);
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 6px; 
            font-weight: bold;
            text-transform: uppercase;
            transition: 0.4s;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .btn-emas:hover {
            background: var(--emas-bright);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .footer-text {
            margin-top: 35px;
            font-size: 0.8rem;
            color: #6d5543;
            border-top: 1px solid rgba(142, 101, 22, 0.15);
            padding-top: 20px;
        }

        .footer-text a {
            color: var(--emas-bright);
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .footer-text a:hover {
            color: #e5c07b;
        }

        .error-msg {
            color: #b35e5e;
            font-size: 0.8rem;
            margin-bottom: 20px;
            border-left: 2px solid #b35e5e;
            padding: 5px 10px;
            text-align: left;
            background: rgba(179, 94, 94, 0.05);
        }
    </style>
</head>
<body>

    <div class="card-daftar">
        <div class="profile-siluet">
            <i class="bi bi-person-plus"></i>
        </div>
        
        <h2>Registrasi</h2>

        <?php if ($err): ?>
            <div class="error-msg">
                <i class="bi bi-info-circle me-1"></i> <?= $err; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="text-start">
                <label class="form-label">Username Baru</label>
                <input type="text" name="username" class="form-control" placeholder="Contoh: batik_user" required autocomplete="off">
                
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>

            <button type="submit" name="daftar" class="btn btn-emas">Buat Akun Sekarang</button>
        </form>

        <div class="footer-text">
            Sudah memiliki akun? 
            <a href="login.php">Masuk di sini</a> </div>
    </div>

</body>
</html>