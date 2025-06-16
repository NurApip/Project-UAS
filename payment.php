<?php
session_start();
include('server/connection.php'); // Hubungkan ke database

// OTENTIKASI: Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

if (isset($_POST['order_pay_btn']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // AMBIL DATA DARI DATABASE (LEBIH AMAN)
    $stmt = $conn->prepare("SELECT order_status, order_cost FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->bind_param('ii', $order_id, $user_id);
    $stmt->execute();
    $order_data = $stmt->get_result()->fetch_assoc();

    if (!$order_data) {
        header('location: account.php?error=Pesanan tidak ditemukan.');
        exit;
    }

    $order_status = $order_data['order_status'];
    $order_total_price = $order_data['order_cost'];

} else {
    header('location: index.php');
    exit;
}

// Menggunakan header dari template Anda
include('layouts/header.php'); 
?>

<style>
    :root {
        --dark-bg: #16181a;
        --light-bg: #1b2838;
        --card-bg: #2a475e;
        --accent-color: #66c0f4;
        --text-primary: #c7d5e0;
        --text-secondary: #a0a7b8;
        --success-color: #4dffaf;
    }
    .payment-section {
        padding: 60px 0;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .payment-wrapper {
        background-color: var(--light-bg);
        padding: 40px;
        border-radius: 12px;
        max-width: 600px;
        width: 100%;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .payment-wrapper h4 {
        color: #fff;
        font-weight: 700;
        font-size: 24px;
        margin-bottom: 10px;
    }
    .payment-wrapper .total-price {
        font-size: 18px;
        color: var(--text-secondary);
        margin-bottom: 25px;
    }
    .payment-wrapper .total-price strong {
        color: var(--success-color);
        font-size: 22px;
    }
    .payment-wrapper .qr-code {
        width: 220px;
        margin: 0 auto 25px auto;
        border: 4px solid var(--card-bg);
        padding: 10px;
        border-radius: 8px;
        background: #fff;
    }
    .payment-wrapper .upload-label {
        font-weight: 600;
        color: var(--text-primary);
        display: block;
        margin-bottom: 10px;
    }
    .payment-wrapper .form-control {
        background-color: var(--dark-bg);
        border: 1px solid var(--card-bg);
        color: var(--text-primary);
        max-width: 350px;
        margin: 0 auto 25px auto;
    }
    /* Menggunakan class 'site-btn' dari template Anda jika ada */
    .btn-submit-payment {
        background-color: var(--accent-color);
        color: var(--dark-bg);
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-submit-payment:hover {
        background-color: #fff;
        transform: translateY(-2px);
    }
    .btn-submit-payment:disabled {
        background-color: #555;
        cursor: not-allowed;
    }
    .payment-success-message {
        color: var(--success-color);
        font-weight: 700;
    }
</style>


<section class="payment-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="payment-wrapper">
                    <?php if ($order_status == "not paid") { ?>
                        <h4>Scan QR Code untuk Pembayaran</h4>
                        <p class="total-price">Total Pembayaran: <strong>Rp <?php echo number_format($order_total_price, 0, ',', '.'); ?></strong></p>

                        <img src="img/qrcode-dana.jpg" alt="QR Dana" class="qr-code">

                        <form id="payment-form" action="server/complete_payment.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                            
                            <div>
                                <label for="bukti" class="upload-label">Upload Bukti Pembayaran:</label>
                                <input type="file" name="bukti_pembayaran" id="bukti" accept="image/*" required class="form-control">
                            </div>
                            
                            <button type="submit" class="btn-submit-payment" id="pay-btn">
                                ✅ Konfirmasi Pembayaran
                            </button>
                        </form>
                        <script>
                            document.getElementById('payment-form').addEventListener('submit', function(e) {
                                const btn = document.getElementById('pay-btn');
                                btn.disabled = true;
                                btn.innerHTML = '<span class="payment-success-message">Memproses...</span>';
                            });
                        </script>
                    <?php } else { ?>
                        <h4>Pesanan Sudah Lunas</h4>
                        <p>Anda tidak memiliki tagihan untuk pesanan ini.</p>
                        <a href="account.php" class="site-btn">Kembali ke Akun</a>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</section>
<?php 
// Menggunakan footer dari template Anda
include('layouts/footer.php'); 
?>