<?php
    ob_start();
    session_start();
    include('layouts/header.php');

    if (!isset($_SESSION['admin_logged_in'])) {
        header('location: login.php');
    }
?>

<?php 
    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
        $query_order_edit = "SELECT * FROM orders WHERE order_id = ?";
        $stmt_order_edit = $conn->prepare($query_order_edit);
        $stmt_order_edit->bind_param('i', $order_id);
        $stmt_order_edit->execute();
        $orders = $stmt_order_edit->get_result();

    } else if (isset($_POST['edit_btn'])) {
        $o_id = $_POST['order_id'];
        $o_status = $_POST['order_status'];

        $query_update_status = "UPDATE orders SET order_status = ? WHERE order_id = ?";

        $stmt_update_status = $conn->prepare($query_update_status);
        $stmt_update_status->bind_param('si', $o_status, $o_id);

        if ($stmt_update_status->execute()) {
            header('location: orders.php?success_status=Status has been updated successfully');
        } else {
            header('location: orders.php?fail_status=Could not update order status!');
        }
    } else {
        header('location: orders.php');
        exit;
    }
?>

Begin Page Content
<div class="container-fluid mt-4">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800 text-uppercase fw-bolder">Edit Order</h1>
    <nav class="mt-4 rounded" aria-label="breadcrumb">
        <ol class="breadcrumb px-3 py-2 rounded mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="orders.php">Orders</a></li>
            <li class="breadcrumb-item active">Edit Order</li>
        </ol>
    </nav>

    DataTales Example
    <div class="card shadow mb-4 ">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <form id="edit-form" method="POST" action="order_edit.php">
                        <div class="row">
                            <?php foreach ($orders as $order) { ?>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Order ID</label>
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>" />
                                        <input class="form-control" type="text" value="<?php echo $order['order_id']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Order Status</label>
                                        <select class="form-control" name="order_status" <?php if ($order['order_status'] == 'not paid') echo 'disabled'; ?>>
                                            <option value="" disabled <?php if ($order['order_status'] == 'not paid') echo 'selected'; ?>>Not Paid</option>
                                            <option value="preparing" <?php if ($order['order_status'] == 'preparing') echo 'selected'; ?>>Preparing</option>
                                            <option value="delivering" <?php if ($order['order_status'] == 'delivering') echo 'selected'; ?>>Delivering</option>
                                            <option value="done" <?php if ($order['order_status'] == 'done') echo 'selected'; ?>>Done</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>User ID</label>
                                        <input class="form-control" type="text" value="<?php echo $order['user_id']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Order Date</label>
                                        <input class="form-control" type="text" value="<?php echo $order['order_date']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>User Phone</label>
                                        <input class="form-control" type="text" value="<?php echo $order['user_phone']; ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>User Address</label>
                                        <input class="form-control" type="text" value="<?php echo $order['user_address']; ?>" disabled>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="m-t-20 text-right">
                            <a href="orders.php" class="btn btn-outline-danger">Cancel <i class="fas fa-undo"></i></a>
                            <button type="submit" class="btn btn-outline-primary submit-btn" name="edit_btn">Update <i class="fas fa-share-square"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include('layouts/footer.php'); ?>
