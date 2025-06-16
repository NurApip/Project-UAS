<?php
    include('server/connection.php');

    $products = null;

    // Pengaturan Paginasi
    $items_per_page = 12;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($current_page < 1) {
        $current_page = 1;
    }
    $offset = ($current_page - 1) * $items_per_page;

    // Prioritaskan pencarian (POST) terlebih dahulu
    if (isset($_POST['cari'])) {
        $keyword_input = $_POST['keyword'];
        $keyword = "%" . strtolower($keyword_input) . "%";
        $query_products = "SELECT * FROM products WHERE LOWER(product_name) LIKE ? OR LOWER(product_category) LIKE ?";
        
        $stmt_products = $conn->prepare($query_products);
        if ($stmt_products) {
            $stmt_products->bind_param('ss', $keyword, $keyword);
            $stmt_products->execute();
            $products = $stmt_products->get_result();
        }
    // Jika tidak ada pencarian, baru cek filter kategori (GET)
    } else if (isset($_GET['category'])) {
        $category = $_GET['category'];
        if ($category === 'All') {
            $query_products = "SELECT * FROM products LIMIT ?, ?";
            $stmt_products = $conn->prepare($query_products);
            if($stmt_products){
                $stmt_products->bind_param('ii', $offset, $items_per_page);
                $stmt_products->execute();
                $products = $stmt_products->get_result();
            }
        } else {
            $query_products = "SELECT * FROM products WHERE product_category = ? LIMIT ?, ?";
            $stmt_products = $conn->prepare($query_products);
            if($stmt_products){
                $stmt_products->bind_param('sii', $category, $offset, $items_per_page);
                $stmt_products->execute();
                $products = $stmt_products->get_result();
            }
        }
    // Jika tidak ada keduanya, tampilkan default
    } else {
        $query_products = "SELECT * FROM products LIMIT ?, ?";
        $stmt_products = $conn->prepare($query_products);
        if($stmt_products){
            $stmt_products->bind_param('ii', $offset, $items_per_page);
            $stmt_products->execute();
            $products = $stmt_products->get_result();
        }
    }


    // Kalkulasi total halaman untuk pagination
    if (isset($_POST['cari'])) {
        $keyword = "%" . strtolower($_POST['keyword']) . "%";
        $stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE LOWER(product_name) LIKE ? OR LOWER(product_category) LIKE ?");
        $stmt_total->bind_param('ss', $keyword, $keyword);
        $stmt_total->execute();
        $total_products = $stmt_total->get_result()->fetch_assoc()['total'];
    } else if (isset($_GET['category']) && $_GET['category'] !== 'All') {
        $category = $_GET['category'];
        $stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE product_category = ?");
        $stmt_total->bind_param('s', $category);
        $stmt_total->execute();
        $total_products = $stmt_total->get_result()->fetch_assoc()['total'];
    } else {
        $total_products_query = "SELECT COUNT(*) as total FROM products";
        $total_products_result = $conn->query($total_products_query);
        $total_products = $total_products_result->fetch_assoc()['total'];
    }
    
    $total_pages = ceil($total_products / $items_per_page);
?>

<?php
    include('layouts/header.php');
?>
    
<section class="shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shop__product__option">
                    <!-- Konten filter Anda bisa diletakkan di sini jika ada -->
                </div>
                <div class="row product__filter">
                    <?php 
                    if ($products && $products->num_rows > 0) {
                        while ($row = $products->fetch_assoc()) { 
                    ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product__item__new">
                                <div class="product__item__pic__new" style="background-image: url('img/product/<?php echo htmlspecialchars($row['product_image']); ?>');">
                                    <div class="product__item__text__overlay">
                                        <h6 class="product__item__title"><?php echo htmlspecialchars($row['product_name']); ?></h6>
                                        <div class="product__item__hover__content">
                                            <form method="POST" action="shopping-cart.php" style="width:100%;">
                                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                                <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
                                                <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                                                <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>">
                                                <input type="hidden" name="product_description" value="<?php echo htmlspecialchars($row['product_description']); ?>">
                                                <input type="hidden" name="product_quantity" value="1">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <a href="detail-game.php?product_id=<?php echo $row['product_id']; ?>" class="product-item-link-overlay"></a>
                            </div>
                        </div>
                    <?php 
                        }
                    } else {
                        echo "<div class='col-12'><p class='text-center text-white'>Tidak ada produk yang ditemukan.</p></div>";
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__pagination">
                            <?php 
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    $link = "?page=" . $i;
                                    if (isset($_GET['category'])) {
                                        $link .= "&category=" . urlencode($_GET['category']);
                                    }
                                    $active_class = ($i == $current_page) ? 'class="active"' : '';
                                    echo "<a {$active_class} href='{$link}'>{$i}</a>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    include('layouts/footer.php');
?>
