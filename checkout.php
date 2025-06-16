<?php
session_start();
// SAMAKAN DENGAN PATH FILE KONEKSI DATABASE ANDA
include('server/connection.php'); 

if (empty($_SESSION['cart'])) {
    // header('location: index.php');
    // exit();
}

include('layouts/header.php');
?>

<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Pastikan nama file CSS ini benar -->
<link rel="stylesheet" href="your-checkout-styles.css">

<section class="checkout spad">
    <div class="container">
        <div class="row">

            <!-- KOLOM KIRI: Detail Game dengan Gambar & Deskripsi -->
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="checkout-visual-summary-box">
                    <h4 class="checkout-visual-title">Detail Game Pesanan Anda</h4>
                    
                    <div class="visual-game-list">
                        <?php if (!empty($_SESSION['cart'])) { ?>
                            <?php 
                               // Loop melalui setiap item di keranjang
                               foreach ($_SESSION['cart'] as $key => $value) { 
                                   // Ambil product_id dari setiap item
                                   $product_id = $value['product_id'];
                                   // Query ke database untuk mengambil detail lengkap produk
                                   $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? LIMIT 1");
                                   $stmt->bind_param('i', $product_id);
                                   $stmt->execute();
                                   // Ambil hasil query
                                   $product_details = $stmt->get_result()->fetch_assoc();
                            ?>
                                <div class="visual-game-item">
                                    <!-- Menampilkan gambar dari data yang baru diambil dari DB -->
                                    <div class="visual-game-image" style="background-image: url('img/product/<?php echo htmlspecialchars($product_details['product_image']); ?>');"></div>
                                    
                                    <div class="visual-game-info">
                                        <!-- Menampilkan nama dari data yang baru diambil dari DB -->
                                        <div class="visual-game-name"><?php echo htmlspecialchars($product_details['product_name']); ?></div>
                                        <div class="visual-game-price">Rp <?php echo number_format($product_details['product_price'], 0, ',', '.'); ?></div>
                                        
                                        <!-- Menampilkan deskripsi dari data yang baru diambil dari DB -->
                                        <div class="visual-game-description">
                                            <?php 
                                                $description = $product_details['product_description'] ?? 'Deskripsi tidak tersedia.';
                                                echo htmlspecialchars(substr($description, 0, 100)) . (strlen($description) > 100 ? '...' : ''); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="no-items-visual">
                                <p>Keranjang Anda kosong.</p>
                                <a href="shop.php" class="btn btn-primary">Belanja Sekarang!</a>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if (!empty($_SESSION['cart'])) { ?>
                        <a href="shopping-cart.php" class="visual-link-to-cart">
                            <i class="fas fa-edit"></i> Ubah Keranjang
                        </a>
                    <?php } ?>
                </div>
            </div>

            <!-- KOLOM KANAN: Rincian Order & Pembayaran -->
            <div class="col-lg-5">
                <div class="checkout-right-column-content"> 
                    <div class="checkout-order-summary-box">
                        <h4 class="checkout-title">Your Order</h4>
                        <div class="checkout-product-header">
                            <span>Produk</span>
                            <span>Harga</span>
                        </div>
                        <ul class="checkout-product-list">
                            <?php if (!empty($_SESSION['cart'])) { ?>
                                <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                                    <li class="checkout-product-item">
                                        <span class="product-name"><?php echo htmlspecialchars($value['product_name']); ?></span>
                                        <span class="product-item-price">Rp <?php echo number_format($value['product_price'], 0, ',', '.'); ?></span>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="checkout-payment-summary-box">
                        <h4 class="payment-summary-title">Ringkasan Pembayaran</h4>
                        <ul class="payment-summary-list">
                            <li>
                                <span>Subtotal</span>
                                <span>Rp <?php echo isset($_SESSION['total']) ? number_format($_SESSION['total'], 0, ',', '.') : '0'; ?></span>
                            </li>
                            <li>
                                <span>Pajak (1%)</span>
                                <?php $pajak = isset($_SESSION['total']) ? $_SESSION['total'] * 0.01 : 0; ?>
                                <span>Rp <?php echo number_format($pajak, 0, ',', '.'); ?></span>
                            </li>
                            <li class="payment-total-row">
                                <strong>Total Pembayaran</strong>
                                <?php $total_pembayaran = isset($_SESSION['total']) ? $_SESSION['total'] + $pajak : 0; ?>
                                <span>Rp <?php echo number_format($total_pembayaran, 0, ',', '.'); ?></span>
                            </li>
                        </ul>
                        
                        <form id="checkout-form" method="POST" action="server/place_order.php" class="mt-auto">
                             <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
                                <button type="submit" class="primary-checkout-btn" name="place_order">
                                    <i class="fas fa-shield-alt"></i> CHECKOUT
                                </button>
                            <?php } else { ?>
                                <div class="alert alert-danger text-center">
                                    Harap login untuk memesan.
                                    <a href="login.php" class="btn btn-sm btn-light mt-2">Login atau Daftar</a>
                                </div>
                                <button type="button" class="primary-checkout-btn" disabled>CHECKOUT</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
    include('layouts/footer.php');
?>
