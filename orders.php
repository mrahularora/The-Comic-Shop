<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';
redirectIfNotLoggedIn();

$db = new Database();
$order = new Order($db->getConnection());
$orders = $order->getOrdersByUser($_SESSION['user_id']);
?>

<?php include 'includes/header.php'; ?>

<main class="orders-main">
    <section class="orders-page">
        <div class="checkout-heading">
            <div>
                <p class="eyebrow">Order tracking</p>
                <h1>My Orders</h1>
                <p>Track your orders, view item details, and download invoices.</p>
            </div>
            <a href="shop.php" class="button text-none">Continue Shopping</a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="error">
                <p>You have not placed any orders yet.<br><br><a href="shop.php" class="button text-none">Browse Comics</a></p>
            </div>
        <?php else: ?>
            <div class="orders-grid">
                <?php foreach ($orders as $item): ?>
                    <article class="order-card">
                        <div>
                            <span class="order-status"><?= htmlspecialchars($item['order_status']) ?></span>
                            <h2>Order #<?= htmlspecialchars($item['id']) ?></h2>
                            <p><?= htmlspecialchars(date('M d, Y', strtotime($item['created_at']))) ?></p>
                        </div>
                        <div class="order-card-meta">
                            <p><span>Items</span><strong><?= htmlspecialchars($item['total_items']) ?></strong></p>
                            <p><span>Total</span><strong>$<?= number_format($item['total_price'], 2) ?></strong></p>
                        </div>
                        <div class="order-card-actions">
                            <a href="orderDetails.php?order_id=<?= htmlspecialchars($item['id']) ?>" class="vbutton text-none">View Details</a>
                            <a href="pdfCheckout.php?order_id=<?= htmlspecialchars($item['id']) ?>&download=1" class="button text-none">Download Invoice</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
