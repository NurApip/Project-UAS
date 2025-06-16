<?php
    ob_start();
    session_start();
    include('layouts/header.php');

    if (!isset($_SESSION['admin_logged_in'])) {
        header('location: login.php');
    }

    $id = $_GET['order_id'];

    // Perform deletion queries
    $query2 = "DELETE FROM order_item WHERE order_id = '$id'";
    $query = "DELETE FROM orders WHERE order_id = '$id'";
    $query3 = "DELETE FROM payments WHERE order_id = '$id'";

    $result = mysqli_query($conn, $query);
    $result2 = mysqli_query($conn, $query2);
    $result3 = mysqli_query($conn, $query3);

    // Set session messages based on query results
    if ($result && $result2 && $result3) {
        $_SESSION['success_status'] = "Order successfully deleted.";
    } else {
        $_SESSION['fail_status'] = "Failed to delete the order.";
    }

    // Redirect back to orders page
    header('location: orders.php');
    exit();
?>
