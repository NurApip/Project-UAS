<?php
session_start();
include('server/connection.php');

// Jika user tidak login, redirect ke login.php
if (!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

// Logika Logout
if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_photo']);
        header('location: login.php');
        exit;
    }
}

// Logika Ganti Password
if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_email = $_SESSION['user_email'];

    if ($password !== $confirm_password) {
        header('location: account.php?error=Password tidak cocok');
    } else if (strlen($password) < 6) {
        header('location: account.php?error=Password minimal harus 6 karakter');
    } else {
        $stmt_change_password = $conn->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
        $stmt_change_password->bind_param('ss', md5($password), $user_email);

        if ($stmt_change_password->execute()) {
            header('location: account.php?success=Password berhasil diperbarui');
        } else {
            header('location: account.php?error=Gagal memperbarui password');
        }
    }
}

// Ambil data user terbaru dari database
$user_data = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt_user = $conn->prepare("SELECT user_name, user_email, user_photo FROM users WHERE user_id = ?");
    $stmt_user->bind_param('i', $user_id);
    $stmt_user->execute();
    $user_data = $stmt_user->get_result()->fetch_assoc();
}

// Get Orders by User Login
if (isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];
    $query_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
    $stmt_orders = $conn->prepare($query_orders);
    $stmt_orders->bind_param('i', $user_id);
    $stmt_orders->execute();
    $user_orders = $stmt_orders->get_result();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya - Lapak Game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');
        :root {
            --dark-bg: #16181a;
            --light-bg: #1b2838;
            --card-bg: #2a475e;
            --accent-color: #66c0f4;
            --text-primary: #c7d5e0;
            --text-secondary: #a0a7b8;
            --success-color: #4dffaf;
            --error-color: #e74c3c;
        }
        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            margin: 0;
            padding: 40px;
        }
        .account-container {
            display: flex;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .account-nav {
            flex-basis: 25%;
            background-color: var(--light-bg);
            border-radius: 12px;
            padding: 20px;
            height: fit-content;
        }
        .profile-summary {
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .profile-summary img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid var(--accent-color);
            margin-bottom: 15px;
            object-fit: cover;
        }
        .profile-summary h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #fff;
        }
        .profile-summary p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 5px;
            word-break: break-all;
        }
        .nav-menu ul {
            list-style: none;
            padding: 0;
        }
        .nav-menu ul li a {
            display: flex;
            align-items: center;
            padding: 15px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: background-color 0.2s ease, color 0.2s ease;
            font-weight: 600;
        }
        .nav-menu ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        .nav-menu ul li a:hover, .nav-menu ul li a.active {
            background-color: var(--card-bg);
            color: #fff;
        }
        .nav-menu ul li a.logout-btn {
            color: var(--error-color);
            cursor: pointer;
        }
        .nav-menu ul li a.logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.15);
            color: #fff;
        }
        .account-content {
            flex-basis: 75%;
        }
        .content-section {
            display: none;
            background-color: var(--light-bg);
            padding: 30px;
            border-radius: 12px;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .content-section.active {
            display: block;
        }
        .content-section h3 {
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 15px;
            margin-bottom: 25px;
            color: #fff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-secondary);
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #3a3f47;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-size: 15px;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        .btn-submit {
            padding: 12px 30px;
            border: none;
            background-color: var(--accent-color);
            color: var(--dark-bg);
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-submit:hover {
            background-color: #fff;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th, .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .orders-table th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
        }
        .orders-table td {
            font-size: 15px;
        }
        .status-paid, .status-sudah.bayar {
            color: var(--success-color);
            font-weight: 700;
        }
        .status-not.paid, .status-belum.bayar {
            color: #f8b600;
            font-weight: 700;
        }
        .details-btn {
            background-color: var(--card-bg);
            color: var(--text-primary);
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border:none;
            cursor: pointer;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .alert.alert-danger {
            background-color: rgba(231, 76, 60, 0.15);
            border: 1px solid var(--error-color);
            color: #f2a299;
        }
        .alert.alert-success {
            background-color: rgba(77, 255, 175, 0.15);
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        /* ===== CSS BARU UNTUK LOGOUT MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0s 0.3s;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease;
        }
        .modal-content {
            background-color: var(--light-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.4);
            text-align: center;
            max-width: 400px;
            width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }
        .modal-content h4 {
            color: #fff;
            font-size: 22px;
            margin-bottom: 15px;
        }
        .modal-content p {
            color: var(--text-secondary);
            margin-bottom: 25px;
        }
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .modal-actions button, .modal-actions a {
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .modal-actions .btn-secondary {
            background-color: #4a4e69;
            color: #fff;
        }
        .modal-actions .btn-danger {
            background-color: var(--error-color);
            color: #fff;
        }
        /* ========================================= */

        @media (max-width: 991px) {
            body { padding: 20px; }
            .account-container { flex-direction: column; }
        }
    </style>
</head>
<body>

    <div class="account-container">
        <!-- Panel Navigasi Kiri -->
        <div class="account-nav">
            <div class="profile-summary">
                <img src="<?php echo 'img/profile/' . htmlspecialchars($user_data['user_photo'] ?? 'default.jpg'); ?>" alt="Foto Profil">
                <h4><?php echo htmlspecialchars($user_data['user_name'] ?? 'Pengguna'); ?></h4>
                <p><?php echo htmlspecialchars($user_data['user_email'] ?? 'Email'); ?></p>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="index.php" class="nav-link"><i class="fas fa-home"></i> Kembali ke Toko</a></li>
                    <li><a href="#orders" class="nav-link active"><i class="fas fa-history"></i> Riwayat Pesanan</a></li>
                    <li><a href="#change-password" class="nav-link"><i class="fas fa-key"></i> Ganti Password</a></li>
                    <!-- ===== PERUBAHAN PADA LINK LOGOUT ===== -->
                    <li><a id="logout-link" class="nav-link logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Konten Kanan -->
        <div class="account-content">
            <!-- Section Riwayat Pesanan -->
            <div id="orders" class="content-section active">
                <h3>Riwayat Pesanan</h3>
                <?php if (isset($_GET['payment_message'])) { ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['payment_message']); ?></div>
                <?php } ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($user_orders)) { while ($order = $user_orders->fetch_assoc()) { ?>
                            <tr>
                                <td>#<?php echo $order['order_id']; ?></td>
                                <td>Rp <?php echo number_format($order['order_cost'], 0, ',', '.'); ?></td>
                                <td><span class="status-<?php echo str_replace(' ','.', strtolower($order['order_status'])); ?>"><?php echo $order['order_status']; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <form method="POST" action="order_details.php" style="margin:0;">
                                        <input type="hidden" value="<?php echo $order['order_id']; ?>" name="order_id"/>
                                        <button type="submit" name="order_details_btn" class="details-btn">Detail</button>
                                    </form>
                                </td>
                            </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>
            <!-- Section Ganti Password -->
            <div id="change-password" class="content-section">
                <h3>Ganti Password</h3>
                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php } ?>
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php } ?>
                <form id="account-form" method="POST" action="account.php">
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-submit" name="change_password">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== POP-UP MODAL UNTUK LOGOUT ===== -->
    <div id="logout-modal" class="modal-overlay">
        <div class="modal-content">
            <h4>Konfirmasi Logout</h4>
            <p>Anda yakin ingin keluar dari akun Anda?</p>
            <div class="modal-actions">
                <button id="cancel-logout-btn" class="btn-secondary">Batal</button>
                <a href="account.php?logout=1" class="btn-danger">Ya, Logout</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const contentSections = document.querySelectorAll('.content-section');

            // Logika untuk navigasi tab
            function showSection(hash) {
                let sectionToShow = hash || '#orders'; 
                contentSections.forEach(section => {
                    section.classList.toggle('active', '#' + section.id === sectionToShow);
                });
                navLinks.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === sectionToShow);
                });
            }
            showSection(window.location.hash);
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId.startsWith('#')) {
                        e.preventDefault();
                        window.location.hash = targetId;
                    }
                });
            });
            window.addEventListener('hashchange', () => showSection(window.location.hash));

            // ===== JAVASCRIPT BARU UNTUK LOGOUT MODAL =====
            const logoutLink = document.getElementById('logout-link');
            const logoutModal = document.getElementById('logout-modal');
            const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

            if (logoutLink && logoutModal && cancelLogoutBtn) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutModal.classList.add('active');
                });

                cancelLogoutBtn.addEventListener('click', function() {
                    logoutModal.classList.remove('active');
                });

                // Tutup modal jika user mengklik area luar modal
                logoutModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        logoutModal.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>
