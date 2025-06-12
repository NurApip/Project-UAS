<?php
ob_start();
session_start();
include('layouts/header.php');
include('../server/connection.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit();
}

$id = $_GET['product_id'];
$old_image = $_GET['product_image'];

// Fetch product details from the database
$query = "SELECT product_name FROM products WHERE product_id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('location: products.php?fail_update_message=Product not found');
    exit();
}

if (isset($_POST['edit_btn'])) {
    $path = "../img/product/" . basename($_FILES['product_image']['name']);
    
    // Delete the old image if it exists
    $old_image_path = "../img/product/" . $old_image;
    if (file_exists($old_image_path)) {
        unlink($old_image_path);
    }

    // Move the new uploaded file
    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $path)) {
        $photo = $_FILES['product_image']['name'];
        
        $q = "UPDATE products SET product_image = '$photo' WHERE product_id = $id";
        if (mysqli_query($conn, $q)) {
            header('location: products.php?success_update_message=Image product has been updated successfully');
        } else {
            header('location: products.php?fail_update_message=Error occurred, try again!');
        }
    } else {
        header('location: products.php?fail_update_message=Error moving the uploaded file');
    }
    exit();
}
?>

<!-- Begin Page Content -->
<div class="container-fluid mt-4">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 text-uppercase fw-bolder">Ubah Gambar</h1>
    <nav class="mt-4 rounded" aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 rounded mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php">Produk</a></li>
            <li class="breadcrumb-item active">Ubah Gambar</li>
        </ol>
    </nav>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <form id="edit-image-form" enctype="multipart/form-data" method="POST" action="edit_image.php?product_id=<?php echo $id; ?>&product_image=<?php echo $old_image; ?>">
                        <div class="mb-3">
                            <label>Nama Produk : <?php echo $product['product_name']; ?></label>
                            <input name="product_image" type="file" class="form-control" required>
                        </div>
                        <div class="text-right">
                            <a href="products.php" class="btn btn-outline-danger">Batal <i class="fas fa-undo"></i></a>
                            <button type="submit" class="btn btn-outline-success submit-btn" name="edit_btn">Perbarui Gambar <i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php include('layouts/footer.php'); ?>
