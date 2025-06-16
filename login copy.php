<?php
session_start();
include('../server/connection.php');

// Jika admin sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header('location: index.php');
    exit;
}

if (isset($_POST['login_btn'])) {
    $email = $_POST['admin_email'];
    // ================== PERBAIKAN UTAMA DI SINI ==================
    // Password tidak lagi di-enkripsi dengan md5()
    $password = $_POST['admin_password']; 
    // ================== AKHIR PERBAIKAN ==================

    $query = "SELECT admin_id, admin_name, admin_email FROM admins WHERE admin_email = ? AND admin_password = ? LIMIT 1";

    $stmt_login = $conn->prepare($query);
    $stmt_login->bind_param('ss', $email, $password);

    if ($stmt_login->execute()) {
        $stmt_login->bind_result($admin_id, $admin_name, $admin_email);
        $stmt_login->store_result();

        if ($stmt_login->num_rows() == 1) {
            $stmt_login->fetch();

            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $admin_name;
            $_SESSION['admin_email'] = $admin_email;
            $_SESSION['admin_logged_in'] = true;

            header('location: index.php?login_success=Login berhasil!');
            exit;
        } else {
            header('location: login.php?error=Email atau password salah');
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
    <title>Admin Login - Lapak Game</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- CSS untuk Desain Halaman Login -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');
        :root { --primary-color: #6a11cb; --secondary-color: #2575fc; --dark-bg: #1a1c20; --light-bg: #24272c; --text-primary: #ffffff; --text-secondary: #a0a7b8; --accent-color: #4dffaf; --success-color: #2ecc71; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Nunito Sans', sans-serif; background-color: var(--dark-bg); color: var(--text-primary); display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container { display: flex; width: 100%; max-width: 900px; min-height: 550px; background-color: var(--light-bg); border-radius: 15px; overflow: hidden; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5); }
        .login-visual { flex-basis: 50%; background-image: linear-gradient(to bottom, rgba(27,40,56,0.7), rgba(27,40,56,0.9)), url('../img/backgrounds/admin-bg.jpg'); background-size: cover; background-position: center; padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .login-visual h1 { font-size: 36px; font-weight: 800; line-height: 1.2; margin-bottom: 15px; }
        .login-visual p { font-size: 16px; line-height: 1.6; color: var(--text-secondary); }
        .login-form-container { flex-basis: 50%; padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .login-form-container h2 { font-size: 28px; font-weight: 700; margin-bottom: 30px; text-align: center; }
        .input-group { position: relative; margin-bottom: 25px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); }
        .input-group input { width: 100%; padding: 15px 15px 15px 45px; border: 1px solid #3a3f47; border-radius: 8px; background-color: var(--dark-bg); color: var(--text-primary); font-size: 16px; transition: border-color 0.3s ease; }
        .input-group input:focus { outline: none; border-color: var(--accent-color); }
        .login-btn { width: 100%; padding: 15px; border: none; border-radius: 8px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); color: white; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: all 0.3s ease; }
        .login-btn:hover { opacity: 0.9; box-shadow: 0 5px 15px rgba(37, 117, 252, 0.4); }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; text-align: center; font-weight: 600; }
        .alert.alert-danger { background-color: rgba(231, 76, 60, 0.15); border: 1px solid #e74a3b; color: #f2a299; }
        .alert.alert-success { background-color: rgba(46, 204, 113, 0.15); border: 1px solid var(--success-color); color: var(--success-color); }
        @media (max-width: 768px) { .login-container { flex-direction: column; max-width: 400px; min-height: auto; } .login-visual { display: none; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-visual">
            <div>
                <h1>Admin Panel</h1>
                <p>Selamat datang di pusat kendali Lapak Game. Silakan login untuk melanjutkan.</p>
            </div>
        </div>
        <div class="login-form-container">
            <h2>Admin Login</h2>
            <form id="login-form" method="POST" action="login.php">
                
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php } ?>
                 <?php if (isset($_GET['register_success'])) { ?>
                    <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($_GET['register_success']); ?></div>
                <?php } ?>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="admin_email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="admin_password" placeholder="Password" required>
                </div>
                
                <button type="submit" class="login-btn" name="login_btn">Login</button>
                
            </form>
        </div>
    </div>
</body>
</html>
