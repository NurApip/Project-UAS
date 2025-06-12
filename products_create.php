<?php
ob_start();
session_start();
include('layouts/header.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
}
?>

<?php
if (isset($_POST['create_btn'])) {
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_description = $_POST['product_description'];
    $product_criteria = $_POST['product_criteria'];
    $product_price = $_POST['product_price'];

    // This is image file
    $product_image = $_FILES['product_image']['tmp_name'];

    // Images name
    $image_name = str_replace(' ', '_', $product_name) . "1.jpg";

    // Check if product name already exists
    $query_check_product = "SELECT * FROM products WHERE product_name = ?";
    $stmt_check_product = $conn->prepare($query_check_product);
    $stmt_check_product->bind_param('s', $product_name);
    $stmt_check_product->execute();
    $result_check_product = $stmt_check_product->get_result();

    if ($result_check_product->num_rows > 0) {
        $_SESSION['error_message'] = "Product name already exists!";
        header('location: products_create.php');
        exit();
    } else {
        // Upload image
        move_uploaded_file($product_image, "../img/product/" . $image_name);

        $query_insert_product = "INSERT INTO products (product_name, product_category, 
            product_criteria, product_description, product_price, product_image) 
            VALUES (?, ?, ?, ?, ?, ?)";

        $stmt_insert_product = $conn->prepare($query_insert_product);

        $stmt_insert_product->bind_param(
            'ssssss',
            $product_name,
            $product_category,
            $product_criteria,
            $product_description,
            $product_price,
            $image_name
        );

        if ($stmt_insert_product->execute()) {
            $_SESSION['success_message'] = "Product has been created successfully";
            header('location: products.php');
            exit();
        } else {
            $_SESSION['fail_message'] = "Could not create product!";
            header('location: products_create.php');
            exit();
        }
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid mt-4">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 text-uppercase fw-bolder">Tambah Produk</h1>
    <nav class="mt-4 rounded" aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 rounded mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php">Produk</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </nav>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <form id="create-form" enctype="multipart/form-data" method="POST" action="products_create.php">
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger mt-3">
                                <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['fail_message'])): ?>
                            <div class="alert alert-danger mt-3">
                                <?= htmlspecialchars($_SESSION['fail_message']); unset($_SESSION['fail_message']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Game</label>
                                    <input class="form-control" type="text" name="product_name" required>
                                </div>
                                <div class="form-group">
                                    <label>Kategori Game</label>
                                    <select class="form-control" name="product_category" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        <option value="Action">Aksi</option>
                                        <option value="RPG">RPG</option>
                                        <option value="Simulation">Simulasi</option>
                                        <option value="Horror">Horor</option>
                                        <option value="Sports">Olahraga</option>
                                        <option value="Puzzle">Teka-Teki</option>
                                        <option value="Offline Games">Game Offline</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Kriteria Produk</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="favourite" name="product_criteria" value="favourite" required>
                                        <label class="custom-control-label" for="favourite">Favorit</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="none" name="product_criteria" value="none" required>
                                        <label class="custom-control-label" for="none">Tidak Ada</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea class="form-control" rows="5" name="product_description"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Harga</label>
                                    <input class="form-control" type="number" name="product_price" min="1">
                                </div>
                                <label>Gambar</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="addImage1" name="product_image" aria-describedby="inputGroupFileAddon01" required>
                                        <label class="custom-file-label" for="addImage">Pilih file...</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-t-20 text-right">
                            <a href="products.php" class="btn btn-outline-danger">Batal <i class="fas fa-undo"></i></a>
                            <button type="submit" class="btn btn-outline-primary submit-btn" name="create_btn">Buat <i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php include('layouts/footer.php'); ?>
