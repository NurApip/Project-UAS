<?php
session_start();

include('layouts/header.php'); 

if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}

if (isset($_POST['edit_btn'])) {
    $o_id = $_POST['order_id'];
    $o_status = $_POST['order_status'];

    $query_update_status = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt_update_status = $conn->prepare($query_update_status);
    $stmt_update_status->bind_param('si', $o_status, $o_id);

    if ($stmt_update_status->execute()) {
        $_SESSION['success_status'] = "Status has been updated successfully";
    } else {
        $_SESSION['fail_status'] = "Could not update order status!";
    }
}

$query_orders = "SELECT o.order_id, o.order_cost, o.order_status, o.order_date, 
                        u.user_name,
                        oi.product_quantity, p.product_name, p.product_image, p.product_price
                 FROM orders o
                 JOIN order_item oi ON o.order_id = oi.order_id 
                 JOIN users u ON o.user_id = u.user_id 
                 JOIN products p ON oi.product_id = p.product_id 
                 ORDER BY o.order_id DESC";

$stmt_orders = $conn->prepare($query_orders);
$stmt_orders->execute();
$orders = $stmt_orders->get_result();

$grup_orders = [];
while ($row = $orders->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($grup_orders[$order_id])) {
        $grup_orders[$order_id] = [
            'order_id' => $row['order_id'],
            'user_name' => $row['user_name'],
            'order_date' => $row['order_date'],
            'order_cost' => $row['order_cost'],
            'order_status' => $row['order_status'],
            'notes' => isset($row['notes']) ? $row['notes'] : '',
            'products' => []
        ];
    }
    $grup_orders[$order_id]['products'][] = [
        'product_name' => isset($row['product_name']) ? $row['product_name'] : '',
        'product_quantity' => isset($row['product_quantity']) ? $row['product_quantity'] : '',
        'product_image' => isset($row['product_image']) ? $row['product_image'] : '',
        'product_price' => isset($row['product_price']) ? $row['product_price'] : '',
    ];
}
?>

<!-- Begin Page Content -->
<div class="container-fluid mt-4">

    <h1 class="h3 mx-3 text-gray-800 text-uppercase fw-bolder">Orders</h1>
    <nav class="mt-4 rounded" aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 rounded mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>
    </nav>

    <?php if (isset($_SESSION['success_status'])) { ?>
        <div class="alert alert-info" role="alert">
            <?php echo $_SESSION['success_status']; unset($_SESSION['success_status']); ?>
        </div>
    <?php } ?>
    <?php if (isset($_SESSION['fail_status'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['fail_status']; unset($_SESSION['fail_status']); ?>
        </div>
    <?php } ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>List Produk</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grup_orders as $order) { ?>
                            <tr class="text-wrap">
                                <td><?php echo $order['order_id']; ?></td>
                                <form method="POST" action="orders.php">
                                    <td>
                                        <div class="form-group d-flex align-items-center">
                                            <select class="form-control" name="order_status" <?php if ($order['order_status'] == 'not paid') echo 'disabled'; ?>>
                                                <option value="not paid" <?php if ($order['order_status'] == 'not paid') echo 'selected'; ?>>Not Paid</option>
                                                <option value="paid" <?php if ($order['order_status'] == 'paid') echo 'selected'; ?>>Paid</option>
                                            </select>
                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                            <button type="submit" name="edit_btn" class="btn btn-outline-secondary btn-sm ml-2 refresh-status" style="background-color: #3AD4D5;">
                                                <i class="fas fa-sync-alt" style="color: white;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </form>
                                <td><?php echo $order['user_name']; ?></td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td>
                                    <?php foreach ($order['products'] as $product) { ?>
                                        • <?php echo $product['product_name']; ?><br>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php foreach ($order['products'] as $product) { ?>
                                        • <?php echo $product['product_quantity']; ?><br>
                                    <?php } ?>
                                </td>
                                <td><?php echo $order['order_cost']; ?></td>
                                <td>
                                    <button class="btn btn-outline-info btn-circle" data-toggle="modal" data-target="#detailModal" data-order='<?php echo json_encode($order); ?>'>
                                        <i class="bx bxs-detail"></i>
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

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Order Details</h5>
            </div>
            <div class="modal-body">
                <form id="edit-form" method="GET" action="orders.php">
                    <div class="form-group">
                        <label>Order ID</label>
                        <input id="order-id" class="form-control" type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input id="user-name" class="form-control" type="text" disabled>
                    </div>
                    <div class="container mt-3">
                        <div class="row g-2">
                            <div class="col-12">
                                <div id="product-details"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Order</label>
                        <input id="order-date" class="form-control" type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label>Status Order</label>
                        <input id="order-status" class="form-control" type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label>Total Harga</label>
                        <input id="order-cost" class="form-control" type="text" disabled>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<script>
    $('#detailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var order = button.data('order');

        var modal = $(this);
        modal.find('#order-id').val(order.order_id);
        modal.find('#user-name').val(order.user_name);
        modal.find('#order-date').val(order.order_date);
        modal.find('#order-status').val(order.order_status);
        modal.find('#order-cost').val(order.order_cost);
        modal.find('#notes').val(order.notes);

        var productDetails = '';
        order.products.forEach(function (product) {
            productDetails += '<div class="d-flex align-items-center mb-2">';
            productDetails += '<img class="ml-3 mr-3" title="product_image" src="../img/product/' + product.product_image + '" style="width: 80px; height: 80px;" />';
            productDetails += '<span>Product : ' + product.product_name + '<br>';
            productDetails += 'Quantity : ' + product.product_quantity + '<br>';
            productDetails += 'Harga : ' + product.product_price + '</span>';
            productDetails += '</div>';
        });

        modal.find('#product-details').html(productDetails);
    });
</script>

<?php include('layouts/footer.php'); ?>
