<?php
    // Pastikan path ke file koneksi sudah benar
    include('connection.php');

    // Tentukan kategori mana yang ingin Anda tampilkan sebagai "Trending"
    // Anda bisa mengganti 'Action' dengan 'RPG', 'Strategy', 'Open World', dll.
    $trending_category = 'Action'; // <-- UBAH DI SINI UNTUK MENGGANTI KATEGORI TRENDING

    // Query baru untuk mengambil produk berdasarkan KATEGORI, bukan kriteria.
    // Tetap mengambil 8 produk teratas.
    $query_trending_product = "SELECT * FROM products WHERE product_category = ? LIMIT 7";

    $stmt_trending_product = $conn->prepare($query_trending_product);
    
    // Bind nama kategori ke dalam query
    $stmt_trending_product->bind_param('s', $trending_category);

    $stmt_trending_product->execute();

    // Mengganti nama variabel agar lebih sesuai, dari $fav_products menjadi $trending_products
    $fav_products = $stmt_trending_product->get_result();
?>
