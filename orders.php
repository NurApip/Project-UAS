<?php
session_start();
include('../server/connection.php');

// Non-aktifkan pengecekan login sesuai permintaan
/*
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
*/

// Logika untuk UPDATE status pesanan
if (isset($_POST['edit_btn'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $stmt_update = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt_update->bind_param('si', $order_status, $order_id);

    if ($stmt_update->execute()) {
        header('location: orders.php?success_status=Status pesanan berhasil diperbarui.');
    } else {
        header('location: orders.php?fail_status=Gagal memperbarui status.');
    }
    exit;
}


// --- LOGIKA PENGAMBILAN DATA DIPERBAIKI ---
// Mengambil semua pesanan dan menggabungkan dengan nama user
$stmt_orders = $conn->prepare("SELECT o.*, u.user_name FROM orders o JOIN users u ON o.user_id = u.user_id ORDER BY o.order_id DESC");
$stmt_orders->execute();
$orders = $stmt_orders->get_result();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Admin Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- CSS untuk Desain Dashboard (Disematkan) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');
        
        :root {
            --bg-dark: #16181a;
            --bg-light: #1b2838;
            --primary: #66c0f4;
            --secondary: #4dffaf;
            --text-light: #c7d5e0;
            --text-muted: #a0a7b8;
            --border-color: rgba(255, 255, 255, 0.1);
            --danger: #e74a3b;
            --warning: #f6c23e;
            --success: #1cc88a;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            display: flex;
            font-size: 14px;
        }
        .sidebar {
            width: 250px;
            background-color: var(--bg-light);
            padding: 20px;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
        }
        .sidebar-header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); }
        .sidebar-header .logo { font-size: 24px; font-weight: 800; color: #fff; text-decoration: none; letter-spacing: 1px; }
        .sidebar-nav ul { list-style: none; }
        .sidebar-nav ul li a { display: flex; align-items: center; padding: 12px 15px; color: var(--text-light); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: background-color 0.2s ease, color 0.2s ease; }
        .sidebar-nav ul li a.active, .sidebar-nav ul li a:hover { background-color: var(--primary); color: #fff; }
        .sidebar-nav ul li a i { margin-right: 15px; width: 20px; text-align: center; }
        .sidebar-footer { margin-top: auto; }
        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .main-header h1 { font-size: 28px; font-weight: 700; color: #fff; }
        
        .notifications { margin-bottom: 20px; }
        .notifications .alert { padding: 15px; border-radius: 8px; color: #fff; font-weight: 600; }
        .notifications .alert.alert-success { background-color: rgba(77, 255, 175, 0.2); border: 1px solid var(--secondary); color: var(--secondary); }
        .notifications .alert.alert-danger { background-color: rgba(231, 76, 60, 0.2); border: 1px solid var(--danger); color: var(--danger); }
        
        .content-table { background-color: var(--bg-light); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 15px; }
        .admin-table thead tr { border-bottom: 2px solid var(--primary); }
        .admin-table th { padding: 12px 15px; text-align: left; font-weight: 700; color: #fff; text-transform: uppercase; font-size: 13px; }
        .admin-table tbody tr { border-bottom: 1px solid var(--border-color); }
        .admin-table tbody tr:last-child { border-bottom: none; }
        .admin-table tbody tr:hover { background-color: #2a475e; }
        .admin-table td { padding: 15px; vertical-align: middle; }
        .status-paid, .status-sudah.bayar { color: var(--success); font-weight: 700; }
        .status-not.paid, .status-belum.bayar { color: var(--warning); font-weight: 700; }
        
        .btn-action { display: inline-flex; justify-content: center; align-items: center; width: 35px; height: 35px; border-radius: 50%; text-decoration: none; color: #fff; margin: 0 4px; transition: background-color 0.2s; border:none; cursor: pointer; }
        .btn-action.btn-edit { background-color: var(--primary); }
        .btn-action.btn-edit:hover { background-color: #87cefa; }
        .btn-action.btn-delete { background-color: var(--danger); }
        .btn-action.btn-delete:hover { background-color: #ff6b6b; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; justify-content: center; }
            .main-content { margin-left: 0; width: 100%; padding: 20px; }
            .sidebar-header { width: 100%; border-bottom: none; margin-bottom: 0; }
            .sidebar-nav, .sidebar-footer { display: contents; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="logo">ADMIN</a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> <span>Produk</span></a></li>
                <li><a href="add_product.php"><i class="fas fa-plus-square"></i> <span>Tambah Produk</span></a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> <span>Pelanggan</span></a></li> 
            </ul>
        </nav>
    </div>

    <main class="main-content">
        <header class="main-header">
            <h1>Manajemen Pesanan</h1>
        </header>

        <div class="notifications">
            <?php if (isset($_GET['success_status'])) { ?>
                <p class="alert alert-success"><?php echo $_GET['success_status']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['fail_status'])) { ?>
                <p class="alert alert-danger"><?php echo $_GET['fail_status']; ?></p>
            <?php } ?>
        </div>

        <div class="content-table">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()) { ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['user_name']; ?></td>
                        <td>Rp <?php echo number_format($order['order_cost'], 0, ',', '.'); ?></td>
                        <td><span class="status-<?php echo str_replace(' ','.', strtolower($order['order_status'])); ?>"><?php echo $order['order_status']; ?></span></td>
                        <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                        <td>
                            <form method="POST" action="orders.php" style="display:inline-flex; gap: 5px;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <select name="order_status" class="form-control-sm">
                                    <option value="not paid" <?php if($order['order_status']=='not paid') echo 'selected';?>>Belum Lunas</option>
                                    <option value="paid" <?php if($order['order_status']=='paid') echo 'selected';?>>Lunas</option>
                                </select>
                                <button type="submit" name="edit_btn" class="btn-action btn-edit" title="Update Status"><i class="fas fa-check"></i></button>
                            </form>
                            
                            <form method="POST" action="order_details.php" style="display:inline-flex;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="order_details_btn" class="btn-action btn-view" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>


    
</body>
</html>
