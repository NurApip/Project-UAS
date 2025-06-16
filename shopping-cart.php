<?php
session_start();


if (isset($_POST['add_to_cart'])) {
    // Jika keranjang sudah ada
    if (isset($_SESSION['cart'])) {
        $products_array_ids = array_column($_SESSION['cart'], "product_id");
        // Cek apakah produk belum ada di keranjang
        if (!in_array($_POST['product_id'], $products_array_ids)) {
            $product_id = $_POST['product_id'];
            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_image' => $_POST['product_image'],
                'product_description' => $_POST['product_description'] ?? '',
                'product_quantity' => $_POST['product_quantity']
            );
            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            // Jika produk sudah ada (opsional: bisa tambahkan pesan)
        }
    // Jika keranjang belum ada
    } else {
        $product_id = $_POST['product_id'];
        $product_array = array(
            'product_id' => $product_id,
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price'],
            'product_image' => $_POST['product_image'],
            'product_description' => $_POST['product_description'] ?? '',
            'product_quantity' => $_POST['product_quantity']
        );
        $_SESSION['cart'][$product_id] = $product_array;
    }

    // ================== PERUBAHAN LOGIKA (REDIRECT) ==================
    // Setelah memproses, redirect kembali ke halaman keranjang untuk mencegah duplikasi
    header('location: shopping-cart.php');
    exit(); // Selalu exit setelah redirect
    // =======================================================================


// LOGIKA UNTUK MENGHAPUS ITEM DARI KERANJANG
} else if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    
    // Redirect kembali setelah menghapus
    header('location: shopping-cart.php');
    exit();

// LOGIKA UNTUK MENGEDIT KUANTITAS
} else if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['product_quantity'];
    $_SESSION['cart'][$product_id]['product_quantity'] = $new_quantity;

    // Redirect kembali setelah mengedit
    header('location: shopping-cart.php');
    exit();
}

// Fungsi untuk menghitung total akan dipanggil setelah semua logika selesai
function calculateTotalCart() {
    $total_price = 0;
    $total_quantity = 0;
    if(isset($_SESSION['cart'])){
        foreach ($_SESSION['cart'] as $key => $value) {
            $price = $value['product_price'];
            $quantity = $value['product_quantity'];
            $total_price += $price * $quantity;
            $total_quantity += $quantity;
        }
    }
    $_SESSION['total'] = $total_price;
    $_SESSION['quantity'] = $total_quantity;
}

calculateTotalCart();

// Mulai bagian HTML setelah semua logika selesai
include('layouts/header.php');
?>

<!-- ============================================================== -->
<!-- BAGIAN HTML DI BAWAH INI TETAP SAMA SEPERTI KODE ANDA SEBELUMNYA -->
<!-- Anda bisa menggunakan desain lama atau desain baru yang sudah kita buat -->
<!-- Saya akan gunakan desain baru yang lebih modern -->
<!-- ============================================================== -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Pastikan file CSS ini ada di folder css/ -->
<link rel="stylesheet" href="css/shopping-cart-styles.css">

<section class="shopping-cart-section">
    <div class="container">
        <h2 class="cart-title">Keranjang Belanja Anda</h2>

        <div class="shopping-cart-container">
            <!-- Daftar Item Keranjang -->
            <div class="cart-items-box">
                <?php if (!empty($_SESSION['cart'])) { ?>
                    <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="img/product/<?php echo htmlspecialchars($value['product_image']); ?>" alt="<?php echo htmlspecialchars($value['product_name']); ?>">
                            </div>
                            <div class="cart-item-info">
                                <h3><?php echo htmlspecialchars($value['product_name']); ?></h3>
                                <p>Harga: Rp <?php echo number_format($value['product_price'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="cart-item-subtotal">
                                <span>Subtotal</span>
                                <strong>Rp <?php echo number_format($value['product_price'] * $value['product_quantity'], 0, ',', '.'); ?></strong>
                            </div>
                            <div class="cart-item-remove">
                                <form method="POST" action="shopping-cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                    <button type="submit" name="remove_product" class="remove-btn" title="Hapus item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="cart-empty-message">Keranjang Anda masih kosong.</p>
                <?php } ?>
            </div>

            <!-- Ringkasan & Aksi -->
            <?php if (!empty($_SESSION['cart'])) { ?>
                <div class="cart-summary-actions">
                    <div class="cart-actions">
                        <a href="shop.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Lanjutkan Belanja
                        </a>
                    </div>
                    <div class="summary-details">
                        <ul>
                            <?php
                                $subtotal = $_SESSION['total'] ?? 0;
                                $pajak = $subtotal * 0.01; // Pajak 1%
                                $total_pembayaran = $subtotal + $pajak;
                            ?>
                            <li><span>Subtotal</span> <strong>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></strong></li>
                            <li><span>Pajak (1%)</span> <strong>Rp <?php echo number_format($pajak, 0, ',', '.'); ?></strong></li>
                            <li class="summary-total"><span>Total Pembayaran</span> <strong class="total-amount">Rp <?php echo number_format($total_pembayaran, 0, ',', '.'); ?></strong></li>
                        </ul>
                         <form method="POST" action="checkout.php" class="w-100">
                             <button type="submit" name="checkout" class="btn btn-primary checkout-btn">
                                Lanjut ke Checkout <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<?php include('layouts/footer.php'); ?>
