<?php
session_start();
include('../server/connection.php');

// Menonaktifkan pengecekan login sesuai permintaan
/*
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
*/

if (isset($_POST['create_btn'])) {
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    
    $spec_os = $_POST['spec_os'];
    $spec_processor = $_POST['spec_processor'];
    $spec_memory = $_POST['spec_memory'];
    $spec_graphics = $_POST['spec_graphics'];
    $spec_storage = $_POST['spec_storage'];

    $image_file = $_FILES['product_image'];
    $image_name = $image_file['name'];
    $tmp_name = $image_file['tmp_name'];

    $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $new_image_name = time() . "_" . str_replace(' ', '_', $product_name) . "." . $image_extension;

    if (move_uploaded_file($tmp_name, "../img/product/" . $new_image_name)) {
        
        $stmt_insert = $conn->prepare("INSERT INTO products (product_name, product_category, product_description, product_price, product_image, spec_os, spec_processor, spec_memory, spec_graphics, spec_storage) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt_insert->bind_param('sssdssssss', $product_name, $product_category, $product_description, $product_price, $new_image_name, $spec_os, $spec_processor, $spec_memory, $spec_graphics, $spec_storage);

        if ($stmt_insert->execute()) {
            header('location: products.php?success_create_message=Produk berhasil ditambahkan.');
        } else {
            header('location: add_product.php?fail_create_message=Gagal menambahkan produk ke database.');
        }
    } else {
        header('location: add_product.php?fail_create_message=Gagal mengunggah gambar.');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Dashboard</title>
    
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
        .notifications .alert { padding: 15px; border-radius: 8px; color: #fff; font-weight: 600; margin-bottom: 20px; }
        .notifications .alert.alert-danger { background-color: rgba(231, 76, 60, 0.2); border: 1px solid var(--danger); color: var(--danger); }
        .content-form { background-color: var(--bg-light); padding: 30px; border-radius: 12px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-muted); }
        .form-control, .form-control-file, select.form-control { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #3a3f47; background-color: var(--dark-bg); color: var(--text-light); font-size: 15px; -webkit-appearance: none; -moz-appearance: none; appearance: none; }
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
        <div class="sidebar-header">
            <a href="index.php" class="logo">ADMIN</a>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a></li>
                <li><a href="products.php"><i class="fas fa-box"></i> <span>Produk</span></a></li>
                <li><a href="add_product.php" class="active"><i class="fas fa-plus-square"></i> <span>Tambah Produk</span></a></li>
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
            <h1>Tambah Produk Baru</h1>
        </header>

        <div class="notifications">
            <?php if(isset($_GET['fail_create_message'])){ ?>
                <p class="alert alert-danger"><?php echo htmlspecialchars($_GET['fail_create_message']); ?></p>
            <?php } ?>
        </div>

        <div class="content-form">
            <form id="create-form" enctype="multipart/form-data" method="POST" action="add_product.php">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Game</label>
                            <input class="form-control" type="text" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select class="form-control" name="product_category" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Action">Action</option>
                                <option value="RPG">RPG</option>
                                <option value="Strategy">Strategy</option>
                                <option value="Simulation">Simulation</option>
                                <option value="Horror">Horror</option>
                                <option value="Sports">Sports</option>
                                <option value="Puzzle">Puzzle</option>
                                <option value="Offline Games">Offline Games</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input class="form-control" type="number" name="product_price" min="1" required>
                        </div>
                           <div class="form-group">
                                <label>Gambar Utama</label>
                                <input type="file" class="form-control-file" name="product_image" required>
                           </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" rows="12" name="product_description" required></textarea>
                        </div>
                    </div>
                </div>
                
                <hr class="form-divider">
                <h5>Spesifikasi Minimum</h5>

                <div class="row">
                       <div class="col-md-6">
                            <div class="form-group">
                                <label>Sistem Operasi (OS)</label>
                                <input class="form-control" type="text" name="spec_os" placeholder="Contoh: Windows 10 64-bit">
                            </div>
                            <div class="form-group">
                                <label>Prosesor</label>
                                <input class="form-control" type="text" name="spec_processor" placeholder="Contoh: Intel Core i5-2500K">
                            </div>
                            <div class="form-group">
                                <label>Memori (RAM)</label>
                                <input class="form-control" type="text" name="spec_memory" placeholder="Contoh: 8 GB RAM">
                            </div>
                       </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                <label>Grafis (VGA)</label>
                                <input class="form-control" type="text" name="spec_graphics" placeholder="Contoh: NVIDIA GeForce GTX 970">
                            </div>
                            <div class="form-group">
                                <label>Penyimpanan</label>
                                <input class="form-control" type="text" name="spec_storage" placeholder="Contoh: 70 GB available space">
                            </div>
                       </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" name="create_btn">Buat Produk</button>
                    <a href="products.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </main>

    </body>
</html>