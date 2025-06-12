<?php
session_start();
include('layouts/header.php');

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode([]);
    exit;
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query_grup_orders = "SELECT o.order_id, o.order_cost, o.order_status, o.order_date, u.user_name, u.user_city, p.product_image, p.product_name, p.product_price, oi.product_quantity, oi.notes
                            FROM orders o
                            JOIN order_item oi ON o.order_id = oi.order_id 
                            JOIN users u ON o.user_id = u.user_id 
                            JOIN products p ON oi.product_id = p.product_id 
                            WHERE o.order_id = ?";

    $stmt_grup_orders = $conn->prepare($query_grup_orders);
    $stmt_grup_orders->bind_param('i', $order_id);
    $stmt_grup_orders->execute();
    $result = $stmt_grup_orders->get_result();

    $grup_orders = [];
    if ($row = $result->fetch_assoc()) {
        $grup_orders = [
            'order_id' => $row['order_id'],
            'user_name' => $row['user_name'],
            'order_date' => $row['order_date'],
            'order_cost' => $row['order_cost'],
            'order_status' => $row['order_status'],
            'notes' => $row['notes'],
            'products' => []
        ];
        
        do {
            $grup_orders['products'][] = [
                'product_name' => $row['product_name'],
                'product_quantity' => $row['product_quantity'],
                'product_image' => $row['product_image'],
                'product_price' => $row['product_price']
            ];
        } while ($row = $result->fetch_assoc());
    }

    echo json_encode($grup_orders);
}
?>

