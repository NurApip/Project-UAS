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

    // Ambil semua produk dari database
    $stmt_products = $conn->prepare("SELECT * FROM products ORDER BY product_id DESC");
    $stmt_products->execute();
    $products = $stmt_products->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Admin Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- ======================= CSS DISEMATKAN DI SINI ======================= -->
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
        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        .sidebar-header .logo {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .sidebar-nav ul {
            list-style: none;
        }
        .sidebar-nav ul li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .sidebar-nav ul li a.active, .sidebar-nav ul li a:hover {
            background-color: var(--primary);
            color: #fff;
        }
        .sidebar-nav ul li a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .sidebar-footer {
            margin-top: auto;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .main-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
        }
        .main-header .btn {
            background-color: var(--primary);
            color: var(--bg-light);
            font-weight: 700;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        .main-header .btn:hover {
            background-color: #fff;
        }
        .notifications {
            margin-bottom: 20px;
        }
        .notifications .alert {
            padding: 15px;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
        }
        .notifications .alert.alert-success { background-color: rgba(77, 255, 175, 0.2); border: 1px solid var(--secondary); color: var(--secondary); }
        .notifications .alert.alert-info { background-color: rgba(102, 192, 244, 0.2); border: 1px solid var(--primary); color: var(--primary); }
        .notifications .alert.alert-danger { background-color: rgba(231, 76, 60, 0.2); border: 1px solid var(--danger); color: var(--danger); }
        
        /* Styles for Admin Table */
        .content-table {
            background-color: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }
        .admin-table thead tr {
            border-bottom: 2px solid var(--primary);
        }
        .admin-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            font-size: 13px;
        }
        .admin-table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }
        .admin-table tbody tr:last-child {
            border-bottom: none;
        }
        .admin-table tbody tr:hover {
            background-color: #2a475e;
        }
        .admin-table td {
            padding: 15px;
            vertical-align: middle;
        }
        .product-thumbnail {
            width: 60px;
            height: 70px;
            border-radius: 6px;
            object-fit: cover;
        }
        .btn-action {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            text-decoration: none;
            color: #fff;
            margin: 0 4px;
            transition: background-color 0.2s;
        }
        .btn-action.btn-edit { background-color: var(--primary); }
        .btn-action.btn-edit:hover { background-color: #87cefa; }
        .btn-action.btn-delete { background-color: var(--danger); }
        .btn-action.btn-delete:hover { background-color: #ff6b6b; }

        @media (max-width: 991px) {
            .sidebar { width: 70px; }
            .sidebar-header .logo, .sidebar-nav span { display: none; }
            .main-content { margin-left: 70px; width: calc(100% - 70px); }
        }
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
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
                <li><a href="products.php" class="active"><i class="fas fa-box"></i> <span>Produk</span></a></li>
                <li><a href="add_product.php"><i class="fas fa-plus-square"></i> <span>Tambah Produk</span></a></li>
             </ul>
        </nav>
    </div>

    <main class="main-content">
        <header class="main-header">
            <h1>Manajemen Produk</h1>
            <a href="add_product.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk Baru</a>
        </header>

        <!-- Pesan Notifikasi -->
        <div class="notifications">
            <?php if (isset($_GET['success_update_message'])) { ?>
                <p class="alert alert-success"><?php echo $_GET['success_update_message']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['fail_update_message'])) { ?>
                <p class="alert alert-danger"><?php echo $_GET['fail_update_message']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['success_delete_message'])) { ?>
                <p class="alert alert-success"><?php echo $_GET['success_delete_message']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['fail_delete_message'])) { ?>
                <p class="alert alert-danger"><?php echo $_GET['fail_delete_message']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['success_create_message'])) { ?>
                <p class="alert alert-success"><?php echo $_GET['success_create_message']; ?></p>
            <?php } ?>
            <?php if (isset($_GET['fail_create_message'])) { ?>
                <p class="alert alert-danger"><?php echo $_GET['fail_create_message']; ?></p>
            <?php } ?>
        </div>

        <!-- Tabel Produk -->
        <div class="content-table">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Nama Game</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) { ?>
                    <tr>
                        <td><?php echo $product['product_id']; ?></td>
                        <td><img src="<?php echo '../img/product/' . $product['product_image']; ?>" class="product-thumbnail"/></td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td>Rp <?php echo number_format($product['product_price'], 0, ',', '.'); ?></td>
                        <td><?php echo $product['product_category']; ?></td>
                        <td>
                            <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="delete_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn-action btn-delete" onclick="return confirm('Anda yakin ingin menghapus produk ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
