<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';
redirectIfNotLoggedIn();

$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$db = new Database();
$order = new Order($db->getConnection());
$orderDetails = $order->getOrderDetails($order_id, $_SESSION['user_id']);

if (empty($orderDetails)) {
    header('Location: orders.php');
    exit();
}

$orderInfo = $orderDetails[0];
$subtotal = array_reduce($orderDetails, function ($carry, $item) {
    return $carry + ($item['product_quantity'] * $item['product_price']);
}, 0);
$tax = $subtotal * 0.13;
$total = (float) $orderInfo['order_total_price'];
?>

<?php include 'includes/header.php'; ?>

<main class="orders-main">
    <section class="orders-page">
        <div class="checkout-heading">
            <div>
                <p class="eyebrow">Order <?= htmlspecialchars($orderInfo['order_status']) ?></p>
                <h1>Order #<?= htmlspecialchars($order_id) ?></h1>
                <p>Placed on <?= htmlspecialchars(date('M d, Y', strtotime($orderInfo['order_date']))) ?></p>
            </div>
            <div>
                <a href="orders.php" class="button text-none">Back to Orders</a>
                <a href="pdfCheckout.php?order_id=<?= htmlspecialchars($order_id) ?>&download=1" class="vbutton text-none">Download Invoice</a>
            </div>
        </div>

        <div class="order-detail-layout">
            <div class="checkout-card">
                <h2>Items</h2>
                <?php foreach ($orderDetails as $item): ?>
                    <div class="checkout-summary-item order-detail-item">
                        <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                        <div>
                            <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                            <span><?= htmlspecialchars($item['product_description']) ?></span>
                            <span>Qty <?= htmlspecialchars($item['product_quantity']) ?> x $<?= number_format($item['product_price'], 2) ?></span>
                        </div>
                        <b>$<?= number_format($item['product_quantity'] * $item['product_price'], 2) ?></b>
                    </div>
                <?php endforeach; ?>
            </div>

            <aside class="checkout-summary">
                <h2>Tracking</h2>
                <div class="tracking-list">
                    <p><strong>Order placed</strong><span><?= htmlspecialchars(date('M d, Y', strtotime($orderInfo['order_date']))) ?></span></p>
                    <p><strong>Status</strong><span><?= htmlspecialchars($orderInfo['order_status']) ?></span></p>
                    <p><strong>Ship to</strong><span><?= htmlspecialchars($orderInfo['shipping_address']) ?>, <?= htmlspecialchars($orderInfo['zip_code']) ?></span></p>
                    <p><strong>Contact</strong><span><?= htmlspecialchars($orderInfo['contact_number']) ?></span></p>
                </div>

                <div class="checkout-totals">
                    <p><span>Subtotal</span><strong>$<?= number_format($subtotal, 2) ?></strong></p>
                    <p><span>Tax</span><strong>$<?= number_format($tax, 2) ?></strong></p>
                    <p class="checkout-total"><span>Total</span><strong>$<?= number_format($total, 2) ?></strong></p>
                </div>
            </aside>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
