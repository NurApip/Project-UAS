<?php
session_start();
include('server/connection.php');

// 1. OTENTIKASI
if (!isset($_SESSION['user_id'])) {
    header('location: login.php?error=Anda harus login untuk melihat detail pesanan');
    exit;
}

// 2. OTORISASI & PENGAMBILAN DATA
if (isset($_POST['order_details_btn']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // Query untuk mengambil data order utama
    $stmt_order = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ? LIMIT 1");
    $stmt_order->bind_param('ii', $order_id, $user_id);
    $stmt_order->execute();
    $order = $stmt_order->get_result()->fetch_assoc();

    if (!$order) {
        header('location: account.php?error=Pesanan tidak ditemukan.');
        exit;
    }

    // Query untuk mengambil item-item, SEKARANG DENGAN JOIN KE PRODUCTS UNTUK MENGAMBIL LINK FILE
    $stmt_items = $conn->prepare("SELECT oi.*, p.product_file FROM order_item oi 
                                  JOIN products p ON oi.product_id = p.product_id 
                                  WHERE oi.order_id = ?");
    $stmt_items->bind_param('i', $order_id);
    $stmt_items->execute();
    $order_items = $stmt_items->get_result();

} else {
    header('location: account.php');
    exit;
}

// Menggunakan header utama website Anda
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
        --error-color: #e74c3c;
    }
    .order-details-container {
        background-color: var(--light-bg);
        padding: 30px;
        border-radius: 12px;
        color: var(--text-primary);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .order-details-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .order-details-header h4 {
        color: #fff;
        font-weight: 700;
    }
    .order-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    .order-info-grid strong {
        color: var(--text-secondary);
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }
    .order-info-grid p {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    .status-paid { color: var(--success-color); }
    .status-not-paid { color: #f8b600; }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .items-table th, .items-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .items-table th {
        text-transform: uppercase;
        font-size: 13px;
        color: var(--text-secondary);
    }
    .item-info { display: flex; align-items: center; }
    .item-info img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-right: 15px; }
    .total-row td { font-size: 18px; font-weight: 700; color: #fff; }
    .total-row .total-amount { color: var(--success-color); }
    
    /* Tombol Aksi */
    .action-buttons { margin-top: 30px; display: flex; gap: 15px; justify-content: flex-end; }
    .btn-action { padding: 10px 25px; border-radius: 6px; text-decoration: none; font-weight: 700; text-align: center; border: none; cursor: pointer; }
    .btn-pay { background-color: var(--success-color); color: var(--dark-bg); }
    .btn-cancel { background-color: var(--error-color); color: #fff; }
    .btn-download { background-color: var(--accent-color); color: var(--dark-bg); font-size: 13px; padding: 8px 15px; }
</style>

<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
            </div>
        </div>
    </div>
</section>
<section class="checkout spad">
    <div class="container">
        <div class="order-details-container">
            <div class="order-details-header">
                <h4>Detail Pesanan #<?php echo $order['order_id']; ?></h4>
            </div>

            <div class="order-info-grid">
                <div>
                    <strong>Tanggal Pesanan:</strong>
                    <p><?php echo date('d F Y, H:i', strtotime($order['order_date'])); ?></p>
                </div>
                <div>
                    <strong>Status Pesanan:</strong>
                    <p class="status-<?php echo str_replace(' ','.', strtolower($order['order_status'])); ?>"><?php echo ucwords($order['order_status']); ?></p>
                </div>
            </div>
            
            <h5>Item yang Dipesan</h5>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-end">Harga</th>
                        <?php if ($order['order_status'] == 'paid') { ?>
                            <th class="text-end">Download</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $order_items->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <div class="item-info">
                                    <img src="img/product/<?php echo $item['product_image']; ?>" style="width: 60px; ...">
                                    <span><?php echo $item['product_name']; ?></span>
                                </div>
                            </td>
                            <td class="text-end">Rp <?php echo number_format($item['product_price'], 0, ',', '.'); ?></td>
                            
                            <?php if ($order['order_status'] == 'paid') { ?>
                                <td class="text-end">
                                    <?php if (!empty($item['product_file'])) { ?>
                                        <a href="downloads/<?php echo $item['product_file']; ?>" class="btn-action btn-download" download>Download</a>
                                    <?php } else { ?>
                                        <span>Tidak Tersedia</span>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td class="text-end" colspan="<?php echo ($order['order_status'] == 'paid') ? '2' : '1'; ?>">Total Pembayaran</td>
                        <td class="text-end total-amount">Rp <?php echo number_format($order['order_cost'], 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="action-buttons">
                <?php if ($order['order_status'] == 'not paid') { ?>
                    <form method="POST" action="payment.php" style="margin:0;">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <button type="submit" name="order_pay_btn" class="btn-action btn-pay">Bayar Sekarang</button>
                    </form>

                    <a href="actionDeleteOrder.php?order_id=<?php echo $order['order_id']; ?>" 
                       class="btn-action btn-cancel" 
                       onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">Batalkan Pesanan</a>
                <?php } ?>
                
                <a href="account.php#orders" class="btn-action" style="background-color: #4a4e69; color: #fff;">Kembali</a>
            </div>

        </div>
    </div>
</section>
<?php
// Menggunakan footer utama website Anda
include('layouts/footer.php');
?>