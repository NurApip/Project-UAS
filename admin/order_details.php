<?php
// Memulai session di baris paling atas
session_start();

// Menggunakan path koneksi yang benar
include('../server/connection.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Admin</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
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
        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .main-header h1 { font-size: 28px; font-weight: 700; color: #fff; }
        .btn-back { background-color: var(--btn-secondary-bg); color: #fff; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-weight: 600; }
        .content-wrapper { background-color: var(--bg-light); padding: 25px; border-radius: 12px; border: 1px solid var(--border-color); }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid var(--border-color); }
        .info-grid h5 { color: var(--primary); font-size: 16px; margin-bottom: 15px; border-left: 3px solid var(--primary); padding-left: 10px;}
        .info-grid p { margin-bottom: 8px; color: var(--text-muted); }
        .info-grid p strong { color: var(--text-light); font-weight: 600; min-width: 70px; display: inline-block;}
        .status-badge { background-color: rgba(102, 192, 244, 0.2); color: var(--primary); padding: 5px 10px; border-radius: 5px; font-weight: 700; }
        .total-badge { font-size: 20px; font-weight: 700; color: var(--secondary); }
        .products-header { font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 15px; }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 15px; }
        .admin-table thead tr { border-bottom: 2px solid var(--primary); }
        .admin-table th { padding: 12px 15px; text-align: left; font-weight: 700; color: #fff; text-transform: uppercase; font-size: 13px; }
        .admin-table tbody tr { border-bottom: 1px solid var(--border-color); }
        .admin-table tbody tr:last-child { border-bottom: none; }
        .admin-table td { padding: 15px; vertical-align: middle; }
        .product-image { max-width: 60px; height: auto; border-radius: 5px; }
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
        <?php
        if (isset($_POST['order_id'])) {
            $order_id = $_POST['order_id'];

            // PASTIKAN ANDA MENGGUNAKAN QUERY YANG LENGKAP INI, BUKAN "SELECT..."
            $query_grup_orders = "SELECT
                                    o.order_id, o.order_cost, o.order_status, o.order_date,
                                    u.user_name, u.user_email,
                                    p.product_name, p.product_image, p.product_price
                                FROM
                                    orders AS o
                                JOIN
                                    users AS u ON o.user_id = u.user_id
                                JOIN
                                    order_item AS oi ON o.order_id = oi.order_id
                                JOIN
                                    products AS p ON oi.product_id = p.product_id
                                WHERE
                                    o.order_id = ?";
            $stmt = $conn->prepare($query_grup_orders);
            $stmt->bind_param('i', $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $grup_orders = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (empty($grup_orders)) {
                        $grup_orders = [
                            'order_id'      => $row['order_id'],
                            'user_name'     => $row['user_name'],
                            'user_email'    => $row['user_email'],
                            'order_date'    => date('d F Y, H:i', strtotime($row['order_date'])),
                            'order_cost'    => number_format($row['order_cost'], 0, ',', '.'),
                            'order_status'  => htmlspecialchars($row['order_status']),
                            'products'      => []
                        ];
                    }
                    $grup_orders['products'][] = [
                        'product_name'      => htmlspecialchars($row['product_name']),
                        'product_image'     => htmlspecialchars($row['product_image']),
                        'product_price'     => number_format($row['product_price'], 0, ',', '.')
                    ];
                }
            }
        ?>

        <header class="main-header">
            <h1>Detail Pesanan #<?php echo htmlspecialchars($order_id); ?></h1>
            <a href="orders.php" class="btn-back">&laquo; Kembali</a>
        </header>

        <div class="content-wrapper">
            <?php if (!empty($grup_orders)) { ?>
                <div class="info-grid">
                    <div class="info-pemesan">
                        <h5>Informasi Pemesan</h5>
                        <p><strong>Nama:</strong> <?php echo $grup_orders['user_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $grup_orders['user_email']; ?></p>
                    </div>
                    <div class="info-pesanan">
                        <h5>Informasi Pesanan</h5>
                        <p><strong>Tanggal:</strong> <?php echo $grup_orders['order_date']; ?></p>
                        <p><strong>Status:</strong> <span class="status-badge"><?php echo ucwords($grup_orders['order_status']); ?></span></p>
                        <p><strong>Total:</strong> <span class="total-badge">Rp <?php echo $grup_orders['order_cost']; ?></span></p>
                    </div>
                </div>

                <h4 class="products-header">Produk yang Dipesan</h4>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th style="text-align: right;">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grup_orders['products'] as $product) : ?>
                            <tr>
                                <td><img src="../img/product/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image"></td>
                                <td><?php echo $product['product_name']; ?></td>
                                <td style="text-align: right;">Rp <?php echo $product['product_price']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            
            <?php } else {
                echo "<p>Pesanan dengan ID yang diberikan tidak ditemukan.</p>";
            }
        } else {
            echo "<p>Akses tidak valid. ID Pesanan tidak disertakan.</p>";
        }
        $conn->close();
        ?>
        </div>
    </main>
    
</body>
</html>