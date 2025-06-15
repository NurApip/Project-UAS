<?php
session_start();

if (isset($_POST['order_pay_btn'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];
    $order_total_price = ($_POST['order_total_price']/15502);
}
?>

<?php
    include('layouts/header.php');
?>

    <!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option" style="background: #22223b; padding: 18px 0; border-radius: 12px; margin-bottom: 30px;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 12px; font-size: 17px;">
            <a href="index.php" style="color: #f2e9e4; background: #4a4e69; padding: 6px 16px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;">Home</a>
            <span style="color: #9a8c98;">&#10095;</span>
            <a href="shop.php" style="color: #f2e9e4; background: #4a4e69; padding: 6px 16px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;">Menu</a>
            <span style="color: #9a8c98;">&#10095;</span>
            <a href="shopping-cart.php" style="color: #f2e9e4; background: #4a4e69; padding: 6px 16px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;">Shopping cart</a>
            <span style="color: #9a8c98;">&#10095;</span>
            <a href="checkout.php" style="color: #f2e9e4; background: #4a4e69; padding: 6px 16px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.2s;">Checkout</a>
            <span style="color: #9a8c98;">&#10095;</span>
            <span style="color: #22223b; background: #f2e9e4; padding: 6px 16px; border-radius: 8px; font-weight: 600;">Payment</span>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <section class="checkout spad" style="margin-top: -90px;">
        <div class="container">
            <div class="checkout__form">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="checkout__input">
                            <?php if (isset($_POST['order_status']) && $_POST['order_status'] == "not paid") { ?>
                                <?php $amount = strval($order_total_price); ?>
                                <?php $order_id = $_POST['order_id']; ?>
                                <!--<input type="submit" class="btn btn-primary" value="PAY NOW" />-->
                                <!-- Set up a container element for the button -->
                                <div id="paypal-button-container"></div>

                            <?php } else if (isset($_SESSION['total']) && $_SESSION['total'] != 0) { ?>
                                <?php $amount = strval($_SESSION['total']); ?>
                                <?php $order_id = $_SESSION['order_id']; ?>
                                <h6 class="checkout__title">TOTAL PAYMENT: Rp. <?php $_SESSION['total'] ; ?></h6>
                                <!--<input type="submit" class="btn btn-primary" value="PAY NOW" /> -->
                                <!-- Set up a container element for the button -->
                                <div id="paypal-button-container"></div>

                            <?php } else { ?>
                                <p>You don't have an order</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->
    
    <!-- Replace "test" with your own sandbox Business account app client ID -->
    <script src="https://www.paypal.com/sdk/js?client-id=AZc7gISngCVfWIqTNzlMZRSCsd7cte4sTB4ZrK7JEJHUGO9CEALMKj4mzo5ZIe2i6DRAiOhJouUWqxXF&currency=USD"></script>

    <script>
        paypal.Buttons({
            // Sets up the transaction when a payment button is clicked
            createOrder: (data, actions) => {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $amount; ?>' // Can also reference a variable or function
                        }
                    }]
                });
            },
            // Finalize the transaction after payer approval
            onApprove: (data, actions) => {
                return actions.order.capture().then(function(orderData) {
                    // Successful capture! For dev/demo purposes:
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    const transaction = orderData.purchase_units[0].payments.captures[0];
                    alert('Transaction ' + transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');

                    window.location.href = "server/complete_payment.php?transaction_id=" + transaction.id + "&order_id=" + <?php echo $order_id; ?>;
                    // When ready to go live, remove the alert and show a success message within this page. For example:
                    // const element = document.getElementById('paypal-button-container');
                    // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                    // Or go to another URL:  actions.redirect('thank_you.html');
                });
            }
        }).render('#paypal-button-container');
    </script>

<?php
    include('layouts/footer.php');
?>