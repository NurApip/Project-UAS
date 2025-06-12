<?php
    ob_start();
    session_start();
    include('layouts/header.php');

    if (!isset($_SESSION['admin_logged_in'])) {
        header('location: login.php');
    }
?>

<?php 
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        $query_edit_product = "SELECT * FROM products WHERE product_id = ?";
        $stmt_edit_product = $conn->prepare($query_edit_product);
        $stmt_edit_product->bind_param('i', $product_id);
        $stmt_edit_product->execute();
        $products = $stmt_edit_product->get_result();

    } else if (isset($_POST['edit_btn'])) {
        $id = $_POST['product_id'];
        $name = $_POST['product_name'];
        $category = $_POST['product_category'];
        $description = $_POST['product_description'];
        $criteria = $_POST['product_criteria'];
        $price = $_POST['product_price'];

            // Check if any data is changed
        $query_check_changes = "SELECT * FROM products WHERE product_id = ?";
        $stmt_check_changes = $conn->prepare($query_check_changes);
        $stmt_check_changes->bind_param('i', $id);
        $stmt_check_changes->execute();
        $result_check_changes = $stmt_check_changes->get_result();
        $old_product = $result_check_changes->fetch_assoc();

        if ($old_product['product_name'] == $name && 
            $old_product['product_category'] == $category &&
            $old_product['product_description'] == $description &&
            $old_product['product_criteria'] == $criteria &&
            $old_product['product_price'] == $price) {
                // No changes made, show alert
            header('location: products.php?fail_update_message=No data updated!');
                exit;
        }
        
        $query_update_product = "UPDATE products SET product_name = ?, product_category = ?, product_description = ?, product_criteria = ?, product_price = ? 
            WHERE product_id = ?";

        $stmt_update_product = $conn->prepare($query_update_product);
        $stmt_update_product->bind_param('ssssdi', $name, $category, $description, $criteria, $price, $id);

        if ($stmt_update_product->execute()) {
            header('location: products.php?success_update_message=Product has been updated successfully');
        } else {
            header('location: products.php?fail_update_message=Error occured, try again!');
        }
    } else {
        header('location: products.php');
        exit;
    }
?>

<!-- Begin Page Content -->
<div class="container-fluid mt-4">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 text-uppercase fw-bolder">Edit Produk</h1>
    <nav class="mt-4 rounded" aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 rounded mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php">Produk</a></li>
            <li class="breadcrumb-item active">Edit Produk</li>
        </ol>
    </nav>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <form id="edit-form" method="POST" action="products_edit.php">
                        <div class="row">
                            <?php foreach ($products as $product) { ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
                                        <label>Nama Game</label>
                                        <input class="form-control" type="text" name="product_name" value="<?php echo $product['product_name']; ?>">
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
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea class="form-control" rows="5" name="product_description"><?php echo $product['product_description']; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    
                                    <div class="form-group">
                                        <label>Kriteria</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="favourite" name="product_criteria" value="favourite" required <?php if ($product['product_criteria'] == 'favourite') echo ' checked'; ?>>
                                            <label class="custom-control-label" for="favourite">Favorit</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="none" name="product_criteria" value="none" required <?php if ($product['product_criteria'] == 'none') echo ' checked'; ?>>
                                            <label class="custom-control-label" for="none">Tidak ada</label>
                                        </div>
                                    </div>
                                        <div class="form-group">
                                        <label>Harga</label>
                                        <input class="form-control" type="text" name="product_price" value="<?php echo $product['product_price']; ?>">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="m-t-20 text-right">
                            <a href="products.php" class="btn btn-outline-danger">Batal <i class="fas fa-undo"></i></a>
                            <button type="submit" class="btn btn-outline-primary submit-btn" name="edit_btn">Update <i class="fas fa-share-square"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php include('layouts/footer.php'); ?>