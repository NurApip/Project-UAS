<?php
session_start();
include('../server/connection.php');

// Non-aktifkan pengecekan login sesuai permintaan
/*
if (!isset($_SESSION['admin_logged_in'])) {
    header('location: login.php');
    exit;
}
*/

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // MENGGUNAKAN PREPARED STATEMENT UNTUK MENCEGAH SQL INJECTION
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param('i', $product_id);

    if ($stmt->execute()) {
        header('location: products.php?success_delete_message=Produk berhasil dihapus.');
    } else {
        header('location: products.php?fail_delete_message=Gagal menghapus produk.');
    }
    exit;

} else {
    // Redirect jika tidak ada product_id
    header('location: products.php');
    exit;
}
?>
