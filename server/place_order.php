<?php
session_start();

// PENTING: Path ke file koneksi harus benar dari lokasi file ini.
// Karena place_order.php ada di dalam folder 'server', path-nya harus kembali satu level.
include('../server/connection.php'); 

// Jika user tidak login
if (!isset($_SESSION['logged_in'])) {
    header('location: ../checkout.php?message=Silakan login untuk melakukan pemesanan');
    exit;
}

// Jika tombol ditekan
if (isset($_POST['place_order'])) {
    // 1. Ambil info user dan order
    $order_cost = $_SESSION['total'];
    $order_status = "not paid";
    $user_id = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');

    // Masukkan data ke tabel 'orders' (Ini sudah benar)
    $stmt_orders = $conn->prepare("INSERT INTO orders (user_id, order_cost, order_status, order_date) VALUES (?, ?, ?, ?)");
    $stmt_orders->bind_param('idss', $user_id, $order_cost, $order_status, $order_date);
    $stmt_status = $stmt_orders->execute();

    if (!$stmt_status) {
        header('location: ../index.php?error=Gagal membuat pesanan');
        exit;
    }

    $order_id = $stmt_orders->insert_id;

    // 2. Masukkan setiap produk dari keranjang ke tabel 'order_item'
    foreach ($_SESSION['cart'] as $key => $value) {
        $product = $_SESSION['cart'][$key];
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_image = $product['product_image'];
        $product_price = $product['product_price'];
        
        // ================== PERBAIKAN LOGIKA DI SINI ==================
        // Query INSERT disesuaikan dengan struktur tabel 'order_item' Anda
        $query_order_items = "INSERT INTO order_item (order_id, product_id, product_name, product_image, product_price) 
                              VALUES (?, ?, ?, ?, ?)";
        
        $stmt_order_items = $conn->prepare($query_order_items);
        // Bind param yang sudah disesuaikan (hanya 5 kolom)
        $stmt_order_items->bind_param('iissd', $order_id, $product_id, $product_name, $product_image, $product_price);
        // ================== AKHIR PERBAIKAN ==================
        
        $stmt_order_items->execute();
    }

    // 3. Kosongkan keranjang
    unset($_SESSION['cart']);
    unset($_SESSION['total']);
    unset($_SESSION['quantity']); // Juga hapus session quantity jika ada

    // Anda menyimpan order_id ke session, yang bisa berguna di account.php
    $_SESSION['order_id'] = $order_id;

    // 4. Arahkan ke halaman account.php
    // Kami tambahkan parameter URL order_success dan order_id
    // Ini bisa Anda gunakan di account.php untuk menampilkan pesan sukses atau detail order
    header('location: ../account.php?order_success=true&order_id=' . $order_id);
    exit; // Sangat penting untuk memanggil exit() setelah header()
}
?>