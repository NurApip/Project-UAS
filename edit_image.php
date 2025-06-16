<?php
session_start();
include('../server/connection.php');

$product_id = null;
$product_name = null;
$product_image_old = null;

// Ambil data produk yang akan diedit dari URL
if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt_product = $conn->prepare("SELECT product_name, product_image FROM products WHERE product_id = ?");
    $stmt_product->bind_param('i', $product_id);
    $stmt_product->execute();
    $product = $stmt_product->get_result()->fetch_assoc();

    if($product){
        $product_name = $product['product_name'];
        $product_image_old = $product['product_image'];
    }
}

// Proses form saat tombol "Update" ditekan
if(isset($_POST['update_image_btn'])){
    $product_id = $_POST['product_id'];
    $product_image_old = $_POST['product_image_old'];

    // Informasi file gambar baru
    $image_file = $_FILES['product_image'];
    $image_name_new = $image_file['name'];
    $tmp_name = $image_file['tmp_name'];

    // Membuat nama file yang unik
    $image_extension = pathinfo($image_name_new, PATHINFO_EXTENSION);
    $new_photo_name = time() . "_" . $product_id . "." . $image_extension;
    
    // Pindahkan file baru
    if(move_uploaded_file($tmp_name, "../img/product/" . $new_photo_name)){

        // Hapus gambar lama jika bukan default.jpg
        if($product_image_old != 'default.jpg' && file_exists("../img/product/".$product_image_old)){
            unlink("../img/product/".$product_image_old);
        }

        // Update database dengan nama gambar baru
        $stmt_update = $conn->prepare("UPDATE products SET product_image = ? WHERE product_id = ?");
        $stmt_update->bind_param('si', $new_photo_name, $product_id);

        if($stmt_update->execute()){
            header('location: products.php?success_update_message=Gambar produk berhasil diperbarui.');
        } else {
            header('location: products.php?fail_update_message=Gagal memperbarui gambar di database.');
        }
        exit;
    } else {
        header('location: products.php?fail_update_message=Gagal mengunggah gambar baru.');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gambar Produk - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/sb-admin-2.css">
</head>
<body>

    <div class="sidebar">
        <!-- Konten sidebar sama seperti file lain -->
    </div>

    <main class="main-content">
        <header class="main-header">
            <h1>Edit Gambar Produk</h1>
        </header>

        <div class="content-form">
            <?php if ($product) { ?>
            <form id="edit-image-form" enctype="multipart/form-data" method="POST" action="edit_image.php">
                <p>Anda akan mengubah gambar untuk produk: <strong><?php echo htmlspecialchars($product_name); ?></strong></p>
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                <input type="hidden" name="product_image_old" value="<?php echo $product_image_old; ?>" />

                <div class="form-group">
                    <label>Gambar Saat Ini</label>
                    <div>
                        <img src="../img/product/<?php echo $product_image_old; ?>" style="width: 150px; border-radius: 8px;" alt="Current Image">
                    </div>
                </div>

                <div class="form-group">
                    <label>Unggah Gambar Baru</label>
                    <input type="file" class="form-control-file" name="product_image" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" name="update_image_btn">Update Gambar</button>
                    <a href="products.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
            <?php } else { ?>
                <p class="alert alert-danger">Produk tidak ditemukan.</p>
            <?php } ?>
        </div>
    </main>

</body>
</html>
