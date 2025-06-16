<?php
    session_start();
    include('../server/connection.php');
    
    // ================== LOGIKA INI DIAKTIFKAN KEMBALI ==================
    // Sekarang setiap orang yang mengakses file ini akan diperiksa
    // apakah sudah login sebagai admin atau belum.
    if (!isset($_SESSION['admin_logged_in'])) {
        header('location: login.php');
        exit;
    }
    // ================== AKHIR PERUBAHAN ==================

    // --- LOGIKA PHP ANDA TETAP UTUH ---
    // Ambil data untuk kartu statistik
    $stmt_total_orders = $conn->prepare("SELECT COUNT(*) AS total_orders FROM orders");
    $stmt_total_orders->execute();
    $total_orders = $stmt_total_orders->get_result()->fetch_assoc()['total_orders'];

    $stmt_total_payments = $conn->prepare("SELECT SUM(order_cost) AS total_payments FROM orders WHERE order_status = 'paid'");
    $stmt_total_payments->execute();
    $total_payments = $stmt_total_payments->get_result()->fetch_assoc()['total_payments'];

    $stmt_total_users = $conn->prepare("SELECT COUNT(*) AS total_users FROM users");
    $stmt_total_users->execute();
    $total_users = $stmt_total_users->get_result()->fetch_assoc()['total_users'];
    
    $stmt_total_products = $conn->prepare("SELECT COUNT(*) AS total_products FROM products");
    $stmt_total_products->execute();
    $total_products = $stmt_total_products->get_result()->fetch_assoc()['total_products'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lapak Game</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- CSS untuk Desain Dashboard (Disematkan) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');
        :root { --bg-dark: #16181a; --bg-light: #1b2838; --primary: #66c0f4; --secondary: #4dffaf; --text-light: #c7d5e0; --text-muted: #a0a7b8; --border-color: rgba(255, 255, 255, 0.1); --danger: #e74a3b; --warning: #f6c23e; --success: #1cc88a; --info-color: #6c5ce7; --btn-secondary-bg: #4a4e69;}
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Nunito Sans', sans-serif; background-color: var(--bg-dark); color: var(--text-light); display: flex; font-size: 14px; }
        .sidebar { width: 250px; background-color: var(--bg-light); padding: 20px; height: 100vh; position: fixed; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); z-index: 100;}
        .sidebar-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); }
        .sidebar-header .logo { font-size: 24px; font-weight: 800; color: #fff; text-decoration: none; letter-spacing: 1px; }
        .sidebar-nav ul { list-style: none; padding: 0; }
        .sidebar-nav ul li a { display: flex; align-items: center; padding: 12px 15px; color: var(--text-light); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: background-color 0.2s ease, color 0.2s ease; }
        .sidebar-nav ul li a.active, .sidebar-nav ul li a:hover { background-color: var(--primary); color: #fff; }
        .sidebar-nav ul li a i { margin-right: 15px; width: 20px; text-align: center; }
        .sidebar-footer { margin-top: auto; }
        .logout-btn { cursor: pointer; }
        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .main-header h1 { font-size: 28px; font-weight: 700; color: #fff; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .stat-card { background-color: var(--bg-light); padding: 25px; border-radius: 12px; display: flex; align-items: center; gap: 20px; border: 1px solid var(--border-color); transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.3); }
        .stat-card .icon { font-size: 28px; width: 60px; height: 60px; border-radius: 50%; display: flex; justify-content: center; align-items: center; flex-shrink: 0; }
        .stat-card .icon.primary { background-color: rgba(102, 192, 244, 0.2); color: var(--primary); }
        .stat-card .icon.secondary { background-color: rgba(77, 255, 175, 0.2); color: var(--secondary); }
        .stat-card .icon.warning { background-color: rgba(248, 182, 0, 0.2); color: #f8b600; }
        .stat-card .icon.info { background-color: rgba(108, 92, 231, 0.2); color: #6c5ce7; }
        .stat-card-info h5 { color: var(--text-muted); font-size: 14px; font-weight: 600; margin-bottom: 5px; text-transform: uppercase; }
        .stat-card-info p { color: #fff; font-size: 24px; font-weight: 700; margin: 0; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; z-index: 1000; opacity: 0; visibility: hidden; transition: opacity 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background-color: var(--bg-light); padding: 30px; border-radius: 12px; text-align: center; max-width: 400px; transform: scale(0.9); transition: transform 0.3s ease; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-content h4 { color: #fff; margin-bottom: 15px; }
        .modal-content p { color: var(--text-muted); margin-bottom: 25px; }
        .modal-actions { display: flex; justify-content: center; gap: 15px; }
        .modal-actions button, .modal-actions a { padding: 10px 25px; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; }
        .modal-actions .btn-secondary { background-color: var(--btn-secondary-bg); color: #fff; }
        .modal-actions .btn-danger { background-color: var(--danger); color: #fff; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="logo">ADMIN</a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> <span>Produk</span></a></li>
                <li><a href="add_product.php"><i class="fas fa-plus-square"></i> <span>Tambah Produk</span></a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> <span>Pelanggan</span></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <nav class="sidebar-nav">
                <ul>
                    <li><a id="logout-link" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </div>
    </div>

    <main class="main-content">
        <header class="main-header">
            <h1>Dashboard</h1>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon primary"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-card-info">
                    <h5>Total Pesanan</h5>
                    <p><?php echo $total_orders; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon secondary"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-card-info">
                    <h5>Total Pendapatan</h5>
                    <p>Rp <?php echo number_format($total_payments ?? 0, 0, ',', '.'); ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon warning"><i class="fas fa-users"></i></div>
                <div class="stat-card-info">
                    <h5>Total Pengguna</h5>
                    <p><?php echo $total_users; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon info"><i class="fas fa-gamepad"></i></div>
                <div class="stat-card-info">
                    <h5>Total Produk</h5>
                    <p><?php echo $total_products; ?></p>
                </div>
            </div>
        </div>
        
    </main>

    <!-- Modal Konfirmasi Logout -->
    <div id="logout-modal" class="modal-overlay">
        <div class="modal-content">
            <h4>Konfirmasi Logout</h4>
            <p>Anda yakin ingin keluar dari dashboard admin?</p>
            <div class="modal-actions">
                <button id="cancel-logout-btn" class="btn btn-secondary">Batal</button>
                <a href="logout.php?logout=1" class="btn btn-danger">Ya, Logout</a>
            </div>
        </div>
    </div>

    <!-- Script untuk Pop-up Logout -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
