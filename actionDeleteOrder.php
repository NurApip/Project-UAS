<?php
session_start();
include 'server/connection.php';

// 1. OTENTIKASI: Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

// Pastikan order_id ada dan valid
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $user_id = $_SESSION['user_id'];

    // 2. OTORISASI: Pastikan pesanan ini adalah milik user yang sedang login
    $stmt_check = $conn->prepare("SELECT order_id FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt_check->bind_param('ii', $order_id, $user_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        
        // 3. KEAMANAN: Gunakan prepared statement
        $stmt_delete_items = $conn->prepare("DELETE FROM order_item WHERE order_id = ?");
        $stmt_delete_items->bind_param('i', $order_id);
        $stmt_delete_items->execute();

        $stmt_delete_order = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt_delete_order->bind_param('i', $order_id);
        $stmt_delete_order->execute();

        // Redirect dengan pesan yang akan ditangkap oleh account.php
        header("location: account.php?success_message=Pesanan berhasil dibatalkan#orders");
        exit;

    } else {
        // Jika pesanan bukan milik user
        header("location: account.php?error=Anda tidak berhak membatalkan pesanan ini#orders");
        exit;
    }
} else {
    // Jika order_id tidak valid
    header("location: account.php?error=Aksi tidak valid#orders");
    exit;
}
?>