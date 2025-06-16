<?php
session_start();
// KARENA FILE INI ADA DI DALAM FOLDER 'server', PATH KONEKSI DIUBAH
include('connection.php');

$payment_status = "failed"; // Status default
$order_id = null;
$order_cost = 0;

if (isset($_POST['order_id']) && isset($_SESSION['user_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];
    $transaction_id = $_POST['transaction_id'] ?? 'N/A'; // Default jika tidak ada
    $payment_date = date('Y-m-d H:i:s');

    // 1. Update order status jadi 'paid'
    $stmt_update = $conn->prepare("UPDATE orders SET order_status = 'paid' WHERE order_id = ?");
    $stmt_update->bind_param('i', $order_id);
    $stmt_update->execute();

    // 2. Insert data payment
    $stmt_insert = $conn->prepare("INSERT INTO payments (order_id, user_id, transaction_id, payment_date) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param('iiss', $order_id, $user_id, $transaction_id, $payment_date);
    $stmt_insert->execute();

    // Jika kedua query berhasil, set status menjadi sukses
    if ($stmt_update->affected_rows > 0 && $stmt_insert->affected_rows > 0) {
        $payment_status = "success";
        
        // Ambil total biaya dari tabel orders untuk ditampilkan
        $stmt_cost = $conn->prepare("SELECT order_cost FROM orders WHERE order_id = ?");
        $stmt_cost->bind_param('i', $order_id);
        $stmt_cost->execute();
        $order_data = $stmt_cost->get_result()->fetch_assoc();
        $order_cost = $order_data['order_cost'];
    }
} else {
    // Jika tidak ada data yang dikirim, redirect ke index
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - Lapak Game</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- ======================= CSS DISEMATKAN DI SINI ======================= -->
    <style>
        /*==================================
        Payment Confirmation Page Styles
        ====================================*/
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');

        :root {
            --dark-bg: #16181a;
            --light-bg: #1b2838;
            --text-primary: #c7d5e0;
            --text-secondary: #a0a7b8;
            --success-color: #4dffaf;
            --error-color: #e74c3c;
            --accent-color: #66c0f4;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-primary);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .payment-container {
            padding: 20px;
        }

        .payment-card {
            background-color: var(--light-bg);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 25px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            color: #fff;
        }

        .icon-circle.success {
            background-color: var(--success-color);
            box-shadow: 0 0 20px rgba(77, 255, 175, 0.4);
        }

        .icon-circle.failed {
            background-color: var(--error-color);
            box-shadow: 0 0 20px rgba(231, 76, 60, 0.4);
        }

        .payment-card h2 {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 15px;
        }

        .payment-card p {
            color: var(--text-secondary);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .order-summary {
            background-color: var(--dark-bg);
            border: 1px solid #3a3f47;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-item:not(:last-child) {
            margin-bottom: 15px;
        }

        .summary-item span {
            color: var(--text-secondary);
        }

        .summary-item strong {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .btn-primary, .btn-secondary {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: var(--dark-bg) !important;
        }

        .btn-primary:hover {
            background-color: #fff;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #4a4e69;
            color: #fff !important;
        }
        .btn-secondary:hover {
            background-color: #5d6289;
        }
    </style>
    <!-- ======================= AKHIR DARI CSS ======================= -->
</head>
<body>

    <div class="payment-container">
        <div class="payment-card">
            <?php if ($payment_status == "success") { ?>
                <!-- TAMPILAN JIKA SUKSES -->
                <div class="icon-circle success">
                    <i class="fas fa-check"></i>
                </div>
                <h2>Pembayaran Berhasil!</h2>
                <p>Terima kasih! Pesanan Anda telah kami terima.</p>
                <div class="order-summary">
                    <div class="summary-item">
                        <span>ID Pesanan</span>
                        <strong>#<?php echo htmlspecialchars($order_id); ?></strong>
                    </div>
                    <div class="summary-item">
                        <span>Total Bayar</span>
                        <strong>Rp <?php echo number_format($order_cost, 0, ',', '.'); ?></strong>
                    </div>
                </div>
                <a href="../account.php#orders" class="btn-primary">Lihat Riwayat Pesanan</a>
            <?php } else { ?>
                <!-- TAMPILAN JIKA GAGAL -->
                <div class="icon-circle failed">
                    <i class="fas fa-times"></i>
                </div>
                <h2>Pembayaran Gagal</h2>
                <p>Maaf, terjadi kesalahan saat memproses pembayaran Anda. Silakan coba lagi atau hubungi dukungan pelanggan.</p>
                <a href="../payment.php?order_id=<?php echo $order_id; ?>" class="btn-secondary">Coba Lagi</a>
            <?php } ?>
        </div>
    </div>

</body>
</html>
