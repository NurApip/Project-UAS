<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and other head elements -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link {
            transition: background-color 0.2s, color 0.2s;
        }

        .nav-link:hover {
            background-color: #37A5A2;
            color: #fff;
        }

        .nav-link:hover span {
            color: #fff;
        }

        .nav-link.active {
            background-color: #fff;
            color: #333;
            border-left: 4px solid #37A5A2;
        }
    </style>
</head>
<body>


<!-- Sidebar -->
<ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-text mx-3"> Lapak Game</div>
    </a>
    
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    
    <!-- Nav Item - Dashboard -->
    <li class="nav-item ">
        <a class="nav-link" href="index.php">
            <i class="bi bi-controller"></i>
            <span>Dashboard</span></a>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Heading -->
    <div class="sidebar-heading">
        Pemesanan
    </div>
    
    <!-- Nav Item - Orders -->
    <li class="nav-item ">
        <a class="nav-link " href="orders.php">
         <i class="bi bi-box-seam-fill"></i>
            <span>Pesanan</span>
        </a>
    </li>
    
    <!-- Nav Item - Products -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class='bi bi-cpu-fill'></i>
            <span>Produk</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manajemen Produk</h6>
                <a class="collapse-item" href="products.php">Daftar Produk</a>
                <a class="collapse-item" href="products_create.php">Tambah Produk</a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Customers -->
    <li class="nav-item">
        <a class="nav-link" href="customers.php">
            <i class="bi bi-people-fill"></i>
            <span>Pelanggan</span></a>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Heading -->
    <div class="sidebar-heading">
        Pengaturan
    </div>
    
    <!-- Nav Item - Account -->
    <li class="nav-item">
        <a class="nav-link" href="#displayAccount" data-toggle="modal">
            <i class='bx bxs-face'></i>
            <span>Akun</span></a>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->

<!-- Modal -->
<div class="modal fade" id="displayAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center text-dark">
                        <h4>
                            <?php 
                            if (isset($_SESSION['admin_name'])) {
                                echo $_SESSION['admin_name'];
                            } 
                            ?>
                        </h4>
                        <p class="d-flex align-items-center">
                            <i class="bi bi-envelope-at mr-2"></i> 
                            <span><?php if (isset($_SESSION['admin_email'])) {
                                echo $_SESSION['admin_email'];
                            } ?></span>
                        </p>
                    </div>
                    <div class="col-12">
                        <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="bi bi-box-arrow-left fa-sm fa-fw mr-2 text-dark-400"></i>
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var path = window.location.href; 
        $(".nav-link").each(function() {
            if (this.href === path) {
                $(this).addClass("active");
            }
        });
    });
</script>

</body>
</html>