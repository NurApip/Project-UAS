<?php
session_start();
include('../server/connection.php');

if (isset($_SESSION['admin_logged_in'])) {
    header('location: index.php');
    exit;
}

if (isset($_POST['login_btn'])) {
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password']; 

    $query = "SELECT admin_id, admin_name, admin_email, admin_password FROM admins WHERE admin_email = ? AND admin_password = ? LIMIT 1";

    $stmt_login = $conn->prepare($query);
    $stmt_login->bind_param('ss', $email, $password);

    if ($stmt_login->execute()) {
        $stmt_login->bind_result($admin_id, $admin_name, $admin_email, $admin_password);
        $stmt_login->store_result();

        if ($stmt_login->num_rows() == 1) {
            $stmt_login->fetch();

            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $admin_name;
            $_SESSION['admin_email'] = $admin_email;
            $_SESSION['admin_password'] = $admin_password;
            $_SESSION['admin_logged_in'] = true;

            header('location: index.php?message=Logged in successfully');
        } else {
            header('location: login.php?error=Could not verify your account');
        }
    } else {
        // Error
        header('location: login.php?error=Something went wrong!');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lapak Game - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<!-- Tambahkan background gradasi hitam putih di body -->
<body id="page-top" style="background: linear-gradient(to right, #ffffff, #000000);">

    <div class="container" style="margin-top: 100px;">
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"
                                style="background-image: url('img/logo.jpg');">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-dark mb-4" style="font-weight: 800;">Selamat Datang</h1>
                                    </div>
                                    <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show bi bi-exclamation-triangle" role="alert">
                                        <?php echo $_GET['error']; ?>
                                    </div>
                                    <?php endif; ?>
                                    <form class="user" id="login-form" enctype="multipart/form-data" method="POST" action="login.php">
                                        <div class="form-group ">
                                            <input type="email" class="form-control form-control-user" name="admin_email" placeholder="Enter Email Address..." autocomplete="off">
                                        </div>
                                        <div class="form-group" style="margin-top: 10px;">
                                            <input type="password" class="form-control form-control-user" name="admin_password" placeholder="Password" autocomplete="off">
                                        </div>
                                        <input type="submit" class="btn btn-outline-primary btn-user btn-block" style="margin-top: 30px; font-weight: 1000;" name="login_btn" value="Login" />
                                    </form>
                                    <div class="text-center" style="margin-top: 15px;">
                                        <a href="register.php">Belum punya akun? Register di sini</a>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</body>

</html>