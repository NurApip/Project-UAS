<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('server/connection.php');

$product_details = null;

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Logika Anda untuk mengambil detail produk utama (TIDAK DIUBAH)
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? LIMIT 1");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product_details = $result->fetch_assoc();
    } else {
        header('location: shop.php?message=Product not found');
        exit;
    }
} else {
    header('location: shop.php');
    exit;
}

include('layouts/header.php');
?>

<!-- Panggil file CSS baru untuk halaman detail -->
<link rel="stylesheet" href="css/detail-game-styles.css">

<section class="game-detail-hero" style="background-image: url('img/product/<?php echo htmlspecialchars($product_details['product_image']); ?>');">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <div class="row">
            <div class="col-lg-8">
                <div class="hero-game-info">
                    <span class="game-category"><?php echo htmlspecialchars($product_details['product_category']); ?></span>
                    <h1 class="game-title"><?php echo htmlspecialchars($product_details['product_name']); ?></h1>
                    <div class="price-and-cart">
                        <div class="game-price">Rp <?php echo number_format($product_details['product_price'], 0, ',', '.'); ?></div>
                        <form method="POST" action="shopping-cart.php">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_details['product_id']); ?>">
                            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product_details['product_image']); ?>">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_details['product_name']); ?>">
                            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product_details['product_price']); ?>">
                            <input type="hidden" name="product_quantity" value="1">
                            <button type="submit" name="add_to_cart" class="btn-add-to-cart"><i class="fas fa-shopping-cart"></i> Tambah ke Keranjang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="game-detail-content spad">
    <div class="container">
        <div class="row">
            <!-- Kolom Kiri: Deskripsi & Spesifikasi -->
            <div class="col-lg-8">
                <div class="game-description">
                    <h3>Tentang Game Ini</h3>
                    <p><?php echo nl2br(htmlspecialchars($product_details['product_description'])); ?></p>
                </div>

                <!-- ================== BAGIAN SPESIFIKASI BARU ================== -->
                <div class="game-specifications">
                    <h3>Spesifikasi Minimum</h3>
                    <div class="spec-grid">
                        <div class="spec-item">
                            <i class="fas fa-desktop"></i>
                            <strong>Sistem Operasi</strong>
                            <span><?php echo htmlspecialchars($product_details['spec_os'] ?? '-'); ?></span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-microchip"></i>
                            <strong>Prosesor</strong>
                            <span><?php echo htmlspecialchars($product_details['spec_processor'] ?? '-'); ?></span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-memory"></i>
                            <strong>Memori</strong>
                            <span><?php echo htmlspecialchars($product_details['spec_memory'] ?? '-'); ?></span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-gamepad"></i>
                            <strong>Grafis</strong>
                            <span><?php echo htmlspecialchars($product_details['spec_graphics'] ?? '-'); ?></span>
                        </div>
                        <div class="spec-item">
                            <i class="fas fa-hdd"></i>
                            <strong>Penyimpanan</strong>
                            <span><?php echo htmlspecialchars($product_details['spec_storage'] ?? '-'); ?></span>
                        </div>
                    </div>
                </div>
                 <!-- ================== AKHIR BAGIAN SPESIFIKASI ================== -->
            </div>

            <!-- Kolom Kanan: Gambar -->
            <div class="col-lg-4">
                <div class="game-main-image">
                    <img src="img/product/<?php echo htmlspecialchars($product_details['product_image']); ?>" alt="<?php echo htmlspecialchars($product_details['product_name']); ?>">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produk Terkait (Related Products) -->
<section class="related-products-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h4 class="section-title-new">Produk Terkait</h4>
            </div>
        </div>
        <div class="row product__filter">
            <?php
                $current_product_category = $product_details['product_category'];
                $current_product_id = $product_details['product_id'];

                $related_stmt = $conn->prepare("SELECT * FROM products WHERE product_category = ? AND product_id != ? LIMIT 4");
                $related_stmt->bind_param('si', $current_product_category, $current_product_id);
                $related_stmt->execute();
                $related_products = $related_stmt->get_result();
            
                while ($related_row = $related_products->fetch_assoc()) {
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item__new">
                        <div class="product__item__pic__new" style="background-image: url('img/product/<?php echo htmlspecialchars($related_row['product_image']); ?>');">
                            <div class="product__item__text__overlay">
                                <h6 class="product__item__title"><?php echo htmlspecialchars($related_row['product_name']); ?></h6>
                                <div class="product__item__hover__content">
                                </div>
                            </div>
                        </div>
                        <a href="detail-game.php?product_id=<?php echo htmlspecialchars($related_row['product_id']); ?>" class="product-item-link-overlay"></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include('layouts/footer.php'); ?>
