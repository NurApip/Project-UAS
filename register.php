<?php
session_start();
include('server/connection.php');

if (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

if (isset($_POST['register_btn'])) {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = md5($_POST['user_password']);

    // gunakan foto default dari sistem
    $user_photo = "default.jpg";

    $query = "INSERT INTO users (user_name, user_email, user_password, user_photo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $user_name, $user_email, $user_password, $user_photo);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $user_email;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['logged_in'] = true;

        header('location: account.php?register_success=You registered successfully!');
        // If account couldn't registered
    } else {
        header('location: register.php?error=Could not create an account at the moment');
    }
}
?>

<?php
include('layouts/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option" style="background: #1a1a2e; padding: 15px 0; margin-bottom: 30px;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 12px; font-size: 17px;">
            <a href="index.php" style="color: #f2e9e4; background: #4a4e69; padding: 6px 16px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;">Home</a>
            <span style="color: #9a8c98;">&#10095;</span>
            <a href="login.php" style="color: #f2e9e4; background: #4a4e69; padding: 6px 16px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;">Login</a>
            <span style="color: #9a8c98;">&#10095;</span>
            <span style="color: #22223b; background: #f2e9e4; padding: 6px 16px; border-radius: 8px; font-weight: 600;">Registrasi</span>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Register Section Begin -->
<section class="checkout spad" style="margin-top: -90px;">
    <div class="container">
        <div class="checkout__form">
            <form id="register-form" method="POST" action="register.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="user_name">Nama</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" required>
                </div>
                <div class="form-group">
                    <label for="user_email">Email</label>
                    <input type="email" class="form-control" id="user_email" name="user_email" required>
                </div>
                <div class="form-group">
                    <label for="user_password">Password</label>
                    <input type="password" class="form-control" id="user_password" name="user_password" required>
                </div>
                <div class="form-group">
                    <label for="user_photo">Foto</label>
                    <input type="file" class="form-control" id="user_photo" name="user_photo" required>
                </div>
                <button type="submit" class="btn btn-primary" name="register_btn">Register</button>
                
                <div class="checkout__input__checkbox">
                    <label>
                        <a id="login-url" href="login.php">Sudah Punya akun? Login disini</a>                        </label>
                    </label>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Register Section End -->

<?php
include('layouts/footer.php');
?>