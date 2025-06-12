<?php
ob_start();
session_start();
include('../server/connection.php');

if (isset($_POST['create_btn'])) {
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    $query_insert_admin = "INSERT INTO admins (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";

    $stmt_insert_admin = $conn->prepare($query_insert_admin);

    $stmt_insert_admin->bind_param(
        'sss',
        $admin_name,
        $admin_email,
        $admin_password
    );

    if ($stmt_insert_admin->execute()) {
        header('location: login.php?success_create_message=Account has been created successfully');
    } else {
        header('location: login.php?fail_create_message=Could not create account!');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Lapak Game - Register</title>

    <!-- Fonts & Template CSS -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700,800,900"
        rel="stylesheet" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />

    <!-- Optional custom styling -->
    <style>
        body.bg-gradient-primary {
            background: linear-gradient(135deg, #000000, #cccccc);
        }

        .bg-register-image {
            background-image: url('img/logo2.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            filter: grayscale(100%);
        }
    </style>

</head>

<body class="bg-gradient-primary">

    <div class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-dark mb-4" style="font-weight: 800;">Buat Akun</h1>
                                    </div>
                                    <form class="user" method="POST" action="register.php">
                                        <div class="form-group">
                                            <input type="text" name="admin_name" class="form-control form-control-user" placeholder="Nama Lengkap" required />
                                        </div>
                                        <div class="form-group" style="margin-top: 10px;">
                                            <input type="email" name="admin_email" class="form-control form-control-user" placeholder="Email Address" required />
                                        </div>
                                        <div class="form-group" style="margin-top: 10px;">
                                            <input type="password" name="admin_password" class="form-control form-control-user" placeholder="Password" required />
                                        </div>
                                        <button type="submit" name="create_btn" class="btn btn-outline-primary btn-user btn-block" style="margin-top: 30px; font-weight: 1000;">
                                            Register Account
                                        </button>
                                    </form>
                                    <div class="text-center" style="margin-top: 15px;">
                                        <a class="small" href="login.php">Sudah punya akun? Login di sini</a>
                                    </div>
                                    <hr />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>