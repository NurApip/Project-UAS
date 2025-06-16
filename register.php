<?php
session_start();
include('server/connection.php');

// Jika user sudah login, redirect ke halaman akun
if (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

// Jika tombol register ditekan
if (isset($_POST['register_btn'])) {

    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = md5($_POST['user_password']); // Sebaiknya gunakan password_hash() untuk keamanan yang lebih baik

    // Cek apakah email sudah terdaftar
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
    $stmt_check->bind_param('s', $user_email);
    $stmt_check->execute();
    $stmt_check->bind_result($num_rows);
    $stmt_check->store_result();
    $stmt_check->fetch();

    if ($num_rows != 0) {
        header('location: register.php?error=Email sudah terdaftar!');
        exit;
    }

    // --- LOGIKA UPLOAD FOTO PROFIL ---
    // Ambil informasi file dari form
    $user_photo_file = $_FILES['user_photo'];
    $user_photo_name = $user_photo_file['name'];
    $tmp_name = $user_photo_file['tmp_name'];

    // Buat nama file yang unik untuk menghindari penimpaan file
    $new_photo_name = time() . "_" . $user_name . "_" . $user_photo_name;

    // Pindahkan file yang diunggah ke folder 'img/profile/'
    // PENTING: Pastikan Anda sudah membuat folder "profile" di dalam folder "img"
    move_uploaded_file($tmp_name, "img/profile/" . $new_photo_name);
    // --- AKHIR LOGIKA UPLOAD ---

    // Query INSERT sekarang menggunakan nama foto yang baru diunggah
    $stmt_insert = $conn->prepare("INSERT INTO users (user_name, user_email, user_password, user_photo) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param('ssss', $user_name, $user_email, $user_password, $new_photo_name);

    // Jika berhasil mendaftar
    if ($stmt_insert->execute()) {
        header('location: login.php?register_success=Akun berhasil dibuat! Silakan login.');
        exit;
    // Jika gagal
    } else {
        header('location: register.php?error=Gagal membuat akun saat ini.');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Lapak Game</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
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
            padding: 20px 0;
        }

        .register-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            background-color: var(--light-bg);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        }

        .register-visual {
            flex-basis: 50%;
            background-image: linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0.9)), url('../img/profile/default.jpg');
            background-size: cover;
            background-position: center;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .register-visual h1 {
            font-size: 36px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
        }

        .register-visual p {
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-secondary);
        }

        .register-form-container {
            flex-basis: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-form-container h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }

        .register-form-container .form-subtitle {
            font-size: 15px;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
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
        
        /* Style khusus untuk input file */
        .input-group input[type="file"] {
            padding: 12px 15px 12px 45px;
            cursor: pointer;
        }
        .input-group input[type="file"]::file-selector-button {
            display: none;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(90deg, var(--accent-color), #2ed573);
            color: #111;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            opacity: 0.9;
            box-shadow: 0 5px 15px rgba(77, 255, 175, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert.alert-danger {
            background-color: rgba(231, 76, 60, 0.15);
            border: 1px solid #e74c3c;
            color: #f2a299;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                max-width: 400px;
                min-height: auto;
            }
            .register-visual {
                display: none;
            }
            .register-form-container {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="register-visual">
            <div>
                <h1>Bergabung dengan Komunitas</h1>
                <p>Daftar sekarang untuk mendapatkan akses ke ribuan game dan penawaran eksklusif.</p>
            </div>
        </div>

        <div class="register-form-container">
            <h2>Buat Akun Baru</h2>
            <p class="form-subtitle">Cepat & mudah untuk memulai</p>

            <form id="register-form" method="POST" action="register.php" enctype="multipart/form-data">
                
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php } ?>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="user_name" placeholder="Nama Lengkap" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="user_email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="user_password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-camera"></i>
                    <input type="file" name="user_photo" accept="image/*" required>
                </div>
                
                <button type="submit" class="register-btn" name="register_btn">Register</button>
                
                <div class="login-link">
                    <a href="login.php">Sudah punya akun? Login disini</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
