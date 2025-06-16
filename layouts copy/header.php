<?php
// Add session_start if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Premium Game Marketplace - Digital Games at Exclusive Prices">
    <meta name="keywords" content="Digital Games, Premium Games, Game Codes, Exclusive Deals">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lapak Game</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <link rel="icon" href="img/icon/logo.jpeg" type="image/jpeg">

    </head>

<body>
    <div id="preloder">
        <div class="loader">
            <div class="loader-inner">
                <div class="loader-line-wrap">
                    <div class="loader-line"></div>
                </div>
                <div class="loader-line-wrap">
                    <div class="loader-line"></div>
                </div>
                <div class="loader-line-wrap">
                    <div class="loader-line"></div>
                </div>
                <div class="loader-line-wrap">
                    <div class="loader-line"></div>
                </div>
                <div class="loader-line-wrap">
                    <div class="loader-line"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <div class="offcanvas__logo">
            <a href="index.php"><img src="img/logoj.png" alt="Lapak Game"></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            <a href="../account.php">Login</a>
            <a href="../account.php?register">Register</a>
        </div>
        <div class="offcanvas__text">
            <p>Unlock premium gaming experiences at exclusive prices</p>
        </div>
        <div class="offcanvas__social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-discord"></i></a>
        </div>
    </div>
    <header class="header">
        <div class="container">
            <div class="row align-items-center header-main-row"> <div class="col-lg-2 col-md-3"> <div class="header__logo">
                        <a href="index.php">
                            <img src="img/logoj.png" alt="Lapak Game">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 d-flex align-items-center header-center-group"> <nav class="header__menu mobile-menu">
                        <ul>
                            <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="index.php") { ?> class="active" <?php } ?>>
                                <a href="../index.php"><i class="fas fa-home mr-2"></i>Home</a>
                            </li>
                            <li <?php if(basename($_SERVER['SCRIPT_NAME'])=="shop.php") { ?> class="active" <?php } ?>>
                                <a href="../shop.php"><i class="fas fa-gamepad mr-2"></i>Games</a>
                            </li>
                            </ul>
                    </nav>

                    <details class="details-dropdown header-category-dropdown">
                        <summary role="button">
                            <a class="button">Kategori</a>
                        </summary>
                        <ul>
                            <form method="POST" action="shop.php">
                                <li><a href="?category=All">All</a></li>
                                <li><a href="?category=Action">Action</a></li>
                                <li><a href="?category=RPG">RPG</a></li>
                                <li><a href="?category=Simulation">Simulation</a></li>
                                <li><a href="?category=Horror">Horror</a></li>
                                <li><a href="?category=Sports">Sports</a></li>
                                <li><a href="?category=Puzzle">Puzzle</a></li>
                                <li><a href="?category=Offline Games">Offline Games</a></li>
                            </form>
                        </ul>
                    </details>

                    <div class="shop__sidebar__search header-search-form">
                        <form action="shop.php" method="POST" class="input-group"> <input type="text" name="keyword" placeholder="Search" class="form-control"> <div class="input-group-append">
                                <button class="btn btn-info" type="submit" name="cari"><img src="img/icon/search.png" alt=""></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 d-flex justify-content-end header-account-cart"> <div class="header__nav__option">
                        <div class="account-wrapper">
                            <a href="../account.php"><img src="img/icon/user.png" alt="Account"></a>
                        </div>
                        <a href="../shopping-cart.php"><img src="img/icon/cart.png" alt="Cart"> 
                            <span><?php if(isset($_SESSION['quantity']) && $_SESSION['quantity'] != 0) { echo $_SESSION['quantity']; } ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fas fa-bars"></i></div>
        </div>
    </header>
</body>