<?php
    ob_start();
    session_start();
    include('layouts/header.php');

    if (!isset($_SESSION['admin_logged_in'])) {
        header('location: login.php');
    }

$id = $_GET['product_id'];

$query = "DELETE FROM products WHERE product_id = '$id'";
$stmt = $conn->prepare($query);

if ($stmt->execute()) {
    header('location: products.php?success_delete_message=Product deleted successfully');
} else {
    header('location: products.php?fail_delete_message=Failed to delete product');
}

die();

?>

