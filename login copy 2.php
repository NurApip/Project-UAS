<?php
    session_start();
    include('server/connection.php');

    // Jika user sudah login, arahkan ke halaman akun
    if (isset($_SESSION['logged_in'])) {
        header('location: account.php');
        exit;
    }

    // Jika tombol login ditekan
    if (isset($_POST['login_btn'])) {
        $email = $_POST['user_email'];
        $password = md5($_POST['user_password']);

        $stmt_login = $conn->prepare("SELECT user_id, user_name, user_email, user_password, user_photo FROM users WHERE user_email = ? AND user_password = ? LIMIT 1");
        $stmt_login->bind_param('ss', $email, $password);
        
        if ($stmt_login->execute()) {
            $stmt_login->bind_result($user_id, $user_name, $user_email, $user_password, $user_photo);
            $stmt_login->store_result();

            if ($stmt_login->num_rows() == 1) {
                $stmt_login->fetch();

                // ================== PERBAIKAN LOGIKA DI SINI ==================
                // Memastikan semua data session diisi dengan informasi user yang baru login
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['user_photo'] = $user_photo;
                $_SESSION['logged_in'] = true;
                // ================== AKHIR PERBAIKAN ==================

                // Mengarahkan ke halaman utama (index.php) setelah login berhasil
                header('location: index.php?login_success=Anda berhasil login!');
                exit;

            } else {
                header('location: login.php?error=Email atau password salah!');
                exit;
            }
        } else {
            header('location: login.php?error=Terjadi kesalahan!');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lapak Game</title>
    
    <!-- Link Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- CSS untuk Desain Halaman Login -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');

        :root {
            --primary-color: #6a11cb;
            --secondary-color: #2575fc;
            --dark-bg: #1a1c20;
            --light-bg: #24272c;
            --text-primary: #ffffff;
            --text-secondary: #a0a7b3;
            --accent-color: #4dffaf;
            --success-color: #2ecc71;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            background-color: var(--light-bg);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        }

        .login-visual {
            flex-basis: 50%;
            background-image: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('img/backgrounds/login-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .login-visual h1 {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
        }

        .login-visual p {
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-secondary);
        }

        .login-form-container {
            flex-basis: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form-container h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-form-container .form-subtitle {
            font-size: 15px;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #3a3f47;
            border-radius: 8px;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            opacity: 0.9;
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.4);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }

        .alert.alert-danger {
            background-color: rgba(231, 76, 60, 0.15);
            border: 1px solid #e74c3c;
            color: #f2a299;
        }
        
        .alert.alert-success {
            background-color: rgba(46, 204, 113, 0.15);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
                min-height: auto;
            }
            .login-visual {
                display: none;
            }
            .login-form-container {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-visual">
            <div>
                <h1>Selamat Datang Kembali</h1>
                <p>Masuk ke akun Anda untuk melanjutkan petualangan di dunia game.</p>
            </div>
        </div>
        <div class="login-form-container">
            <h2>Login Akun</h2>
            <p class="form-subtitle">Gunakan email & password Anda</p>

            <form id="login-form" method="POST" action="login.php">
                
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php } ?>
                 <?php if (isset($_GET['register_success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($_GET['register_success']); ?>
                    </div>
                <?php } ?>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="user_email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="user_password" placeholder="Password" required>
                </div>
                
                <button type="submit" class="login-btn" name="login_btn">Login</button>
                
                <div class="register-link">
                    <a href="register.php">Belum punya akun? Daftar disini</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
