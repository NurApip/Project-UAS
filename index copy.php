<?php
    
    include('server/controller_favourite_product.php');
    include('layouts/header.php');
    ?>

    <link rel="stylesheet" href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css)">
    <link rel="stylesheet" href="css/home.css">

    <section class="hero">
        <div class="hero__slider owl-carousel">

            <div class="hero__items set-bg" data-setbg="img/hero/pictures3.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>GET IT NOW</h6>
                                <h2>GTA VI: EDISI 2025</h2>
                                <p>Dapatkan segera dengan garansi aman dan pengiriman instan setelah rilis.</p>
                                <a href="shop.php" class="primary-btn">PESAN SEKARANG <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero__items set-bg" data-setbg="img/hero/pictures2.png">
                 <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>SELAMAT DATANG DI</h6>
                                <h2>Lapak Game</h2>
                                <p>Platform penjualan game online terbaru dengan berbagai pilihan favorit dan menarik.</p>
                                <a href="shop.php" class="primary-btn">Dapatkan Sekarang <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="trending-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-new">
                        <h2>Trending Game</h2>
                        <p>Game paling populer dan banyak dicari minggu ini.</p>
                    </div>
                </div>
            </div>
            <div class="row product__filter">
                <?php while($row = $fav_products->fetch_assoc()) { ?>
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
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="shop.php" class="btn primary-btn-new">Lihat Semua Games</a>
                </div>
            </div>
        </div>
    </section>
    <section class="game-highlights spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-new">
                        <h2>Game Highlights</h2>
                        <p>Pilihan game terbaik dari berbagai kategori.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="shop.php?category=Action" class="highlight-card set-bg" data-setbg="img/genre/action.jpg">
                        <div class="highlight-overlay">
                            <div class="highlight-text">
                                <span>Action</span>
                                <h3>Game Aksi Pilihan</h3>
                                <p>Petualangan penuh adrenalin dan pertarungan seru.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="shop.php?category=RPG" class="highlight-card set-bg" data-setbg="img/genre/rpg.jpg">
                        <div class="highlight-overlay">
                            <div class="highlight-text">
                                <span>RPG</span>
                                <h3>Game RPG Terbaik</h3>
                                <p>Masuki dunia fantasi dan kembangkan karaktermu.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="shop.php?category=Sports" class="highlight-card set-bg" data-setbg="img/genre/sports.jpg">
                        <div class="highlight-overlay">
                            <div class="highlight-text">
                                <span>Sports</span>
                                <h3>Game Sports Terpopuler</h3>
                                <p>Uji kemampuan dalam simulasi game olahraga.</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <a href="shop.php?category=Offline Games" class="highlight-card set-bg" data-setbg="img/genre/OfflineGame.jpg">
                        <div class="highlight-overlay">
                            <div class="highlight-text">
                                <span>Offline Games</span>
                                <h3>Game Petualangan Offline seru</h3>
                                <p>Telusuri game yang tidak kalah hebat.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>


    
    <section class="instagram-section spad">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="instagram__text__new">
                        <i class="fab fa-instagram"></i>
                        <h2>Follow Us on Instagram</h2>
                        <p>Dapatkan info terbaru, giveaway, dan promo eksklusif dengan mengikuti akun kami.</p>
                        <a href="https://www.instagram.com/lapakgamersid/?utm_source=ig_web_button_share_sheet" target="_blank" class="btn instagram-btn">@lapakgamersid</a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="instagram__pic__new">
                        <div class="instagram-item" style="background-image: url('img/instagram/ig1.png');"></div>
                        <div class="instagram-item" style="background-image: url('img/instagram/ig2.png');"></div>
                        <div class="instagram-item" style="background-image: url('img/instagram/ig3.png');"></div>
                        <div class="instagram-item" style="background-image: url('img/instagram/ig4.png');"></div>
                        <div class="instagram-item" style="background-image: url('img/instagram/ig5.png');"></div>
                        <div class="instagram-item" style="background-image: url('img/instagram/ig6.png');"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    include ('layouts/footer.php');
    ?>