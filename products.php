<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
}
?>

<?php include('layouts/header.php'); ?>

<?php
    $query_products = "SELECT * FROM products";

    $stmt_products = $conn->prepare($query_products);
    $stmt_products->execute();
    $products = $stmt_products->get_result();

    function setRupiah($product_price)
    {
        $result = "Rp. ".number_format($product_price, 0, ',', '.');
        return $result;
    }
?>

<!-- Begin Page Content -->
<div class="container-fluid mt-4">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 text-uppercase fw-bolder">Produk</h1>
    <nav class="mt-4 rounded" aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 rounded mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php">Produk</a></li>
            <li class="breadcrumb-item active">Daftar Produk</li>
        </ol>
    </nav>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (isset($_GET['success_update_message'])) { ?>
                <div class="alert alert-info" role="alert">
                    <?php if (isset($_GET['success_update_message'])) {
                        echo $_GET['success_update_message'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['fail_update_message'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (isset($_GET['fail_update_message'])) {
                        echo $_GET['fail_update_message'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success_delete_message'])) { ?>
                <div class="alert alert-info" role="alert">
                    <?php if (isset($_GET['success_delete_message'])) {
                        echo $_GET['success_delete_message'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['fail_delete_message'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (isset($_GET['fail_delete_message'])) {
                        echo $_GET['fail_delete_message'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success_create_message'])) { ?>
                <div class="alert alert-info" role="alert">
                    <?php if (isset($_GET['success_create_message'])) {
                        echo $_GET['success_create_message'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['fail_create_message'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (isset($_GET['fail_create_message'])) {
                        echo $_GET['fail_create_message'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['image_success'])) { ?>
                <div class="alert alert-info" role="alert">
                    <?php if (isset($_GET['image_success'])) {
                        echo $_GET['image_success'];
                    } ?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['image_failed'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (isset($_GET['image_failed'])) {
                        echo $_GET['image_failed'];
                    } ?>
                </div>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar Game</th>
                            <th>Nama Game</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Kriteria</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                            <tr class="text-wrap">
                                <td><?php echo $product['product_id']; ?></td>
                                <td class="text-center"><img title="product_image" src="<?php echo '../img/product/' . $product['product_image']; ?>" style="width: 80px; height: 80px;" /></td>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo $product['product_category']; ?></td>
                                <td><?php echo $product['product_description']; ?></td>
                                <td><?php echo $product['product_criteria']; ?></td>
                                <td><?php echo setRupiah($product['product_price']); ?></td>
                                <td class="text-center">
                                    <a href="<?php echo 'edit_image.php?product_id=' . $product['product_id'] . '&product_image=' . $product['product_image']; ?>" class="btn btn-outline-success btn-circle">
                                        <i class="bx bx-image-add"></i>
                                    </a>
                                    <a href="products_edit.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-outline-warning btn-circle">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <button class="btn btn-outline-danger btn-circle" data-toggle="modal" data-target="#deleteModal" data-product_id="<?php echo $product['product_id']; ?>">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Batal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                "Yakin ingin menghapus game ini?"
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-outline-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var product_id = button.data('product_id');
        var modal = $(this);
        modal.find('#confirmDeleteButton').attr('href', 'products_delete.php?product_id=' + product_id);
    });
</script>

<?php include('layouts/footer.php'); ?>