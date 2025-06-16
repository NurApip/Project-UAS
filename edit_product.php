<?php
session_start();
include('../server/connection.php');

$product = null;

// Menonaktifkan pengecekan login sesuai permintaan.
/*
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
*/

// Logika untuk mengambil data produk yang akan diedit
if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt_edit = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt_edit->bind_param('i', $product_id);
    $stmt_edit->execute();
    $product = $stmt_edit->get_result()->fetch_assoc();

    if(!$product){
        header('location: products.php?fail_update_message=Produk tidak ditemukan.');
        exit;
    }
} 
// Logika untuk memproses update
else if (isset($_POST['edit_btn'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $category = $_POST['product_category'];
    $description = $_POST['product_description'];
    $price = $_POST['product_price'];
    
    // Mengambil data spesifikasi dari form
    $spec_os = $_POST['spec_os'];
    $spec_processor = $_POST['spec_processor'];
    $spec_memory = $_POST['spec_memory'];
    $spec_graphics = $_POST['spec_graphics'];
    $spec_storage = $_POST['spec_storage'];

    // Query UPDATE sekarang menyertakan kolom spesifikasi dan tanpa 'product_criteria'
    $stmt_update = $conn->prepare("UPDATE products SET product_name=?, product_category=?, product_description=?, product_price=?, spec_os=?, spec_processor=?, spec_memory=?, spec_graphics=?, spec_storage=? WHERE product_id=?");
    $stmt_update->bind_param('sssdssssss', $name, $category, $description, $price, $spec_os, $spec_processor, $spec_memory, $spec_graphics, $spec_storage, $product_id);

    if ($stmt_update->execute()) {
        header('location: products.php?success_update_message=Produk berhasil diperbarui.');
    } else {
        header('location: products.php?fail_update_message=Gagal memperbarui produk.');
    }
    exit();
} else {
    header('location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');
        :root { --bg-dark: #16181a; --bg-light: #1b2838; --primary: #66c0f4; --text-light: #c7d5e0; --text-muted: #a0a7b8; --border-color: rgba(255, 255, 255, 0.1); --danger: #e74a3b; --btn-secondary-bg: #4a4e69; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Nunito Sans', sans-serif; background-color: var(--bg-dark); color: var(--text-light); display: flex; font-size: 14px; }
        .sidebar { width: 250px; background-color: var(--bg-light); padding: 20px; height: 100vh; position: fixed; display: flex; flex-direction: column; border-right: 1px solid var(--border-color); }
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
        .content-form { background-color: var(--bg-light); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-muted); }
        .form-control, select.form-control { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #3a3f47; background-color: var(--dark-bg); color: var(--text-light); font-size: 15px; appearance: none; }
        select.form-control { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23c7d5e0' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right .75rem center; background-size: 16px 12px; }
        textarea.form-control { resize: vertical; min-height: 120px; }
        .form-control:focus { outline: none; border-color: var(--primary); }
        .form-actions { margin-top: 30px; display: flex; gap: 15px; }
        .form-actions .btn { padding: 12px 30px; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-primary { background-color: var(--primary); color: #1b2838; }
        .btn-primary:hover { background-color: #fff; }
        .btn-secondary { background-color: var(--btn-secondary-bg); color: #fff; }
        .btn-secondary:hover { background-color: #5d6289; }
        hr.form-divider { border: none; border-top: 1px solid var(--border-color); margin: 30px 0; }
        .content-form h5 { color: #fff; font-weight: 700; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header"> <a href="index.php" class="logo">ADMIN</a> </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
                <li><a href="products.php" class="active"><i class="fas fa-box"></i> <span>Produk</span></a></li>
                <li><a href="add_product.php"><i class="fas fa-plus-square"></i> <span>Tambah Produk</span></a></li>
                <li><a href="customers.php"><i class="fas fa-users"></i> <span>Pelanggan</span></a></li> 
            </ul>
        </nav>
        <div class="sidebar-footer">
            <nav class="sidebar-nav">
                <ul> 
                    </ul>
            </nav>
        </div>
    </div>

    <main class="main-content">
        <header class="main-header">
            <h1>Edit Produk</h1>
        </header>

        <div class="content-form">
            <?php if ($product) { ?>
            <form id="edit-form" method="POST" action="edit_product.php">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Game</label>
                            <input class="form-control" type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control" name="product_category" required>
                                <option value="Action" <?php if($product['product_category']=='Action') echo 'selected'; ?>>Action</option>
                                <option value="RPG" <?php if($product['product_category']=='RPG') echo 'selected'; ?>>RPG</option>
                                <option value="Strategy" <?php if($product['product_category']=='Strategy') echo 'selected'; ?>>Strategy</option>
                                <option value="Simulation" <?php if($product['product_category']=='Simulation') echo 'selected'; ?>>Simulation</option>
                                <option value="Horror" <?php if($product['product_category']=='Horror') echo 'selected'; ?>>Horror</option>
                                <option value="Sports" <?php if($product['product_category']=='Sports') echo 'selected'; ?>>Sports</option>
                                <option value="Puzzle" <?php if($product['product_category']=='Puzzle') echo 'selected'; ?>>Puzzle</option>
                                <option value="Offline Games" <?php if($product['product_category']=='Offline Games') echo 'selected'; ?>>Offline Games</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input class="form-control" type="number" name="product_price" value="<?php echo $product['product_price']; ?>" min="1" required>
                        </div>
                    </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control" rows="8" name="product_description" required><?php echo htmlspecialchars($product['product_description']); ?></textarea>
                            </div>
                       </div>
                </div>

                <hr class="form-divider">
                <h5>Spesifikasi Minimum</h5>

                <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label>Sistem Operasi (OS)</label>
                                <input class="form-control" type="text" name="spec_os" value="<?php echo htmlspecialchars($product['spec_os'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Prosesor</label>
                                <input class="form-control" type="text" name="spec_processor" value="<?php echo htmlspecialchars($product['spec_processor'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Memori (RAM)</label>
                                <input class="form-control" type="text" name="spec_memory" value="<?php echo htmlspecialchars($product['spec_memory'] ?? ''); ?>">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label>Grafis (VGA)</label>
                                <input class="form-control" type="text" name="spec_graphics" value="<?php echo htmlspecialchars($product['spec_graphics'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Penyimpanan</label>
                                <input class="form-control" type="text" name="spec_storage" value="<?php echo htmlspecialchars($product['spec_storage'] ?? ''); ?>">
                            </div>
                       </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" name="edit_btn">Update Produk</button>
                    <a href="products.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
            <?php } else { ?>
                <p>Produk tidak ditemukan.</p>
            <?php } ?>
        </div>
    </main>

    </body>
</html>