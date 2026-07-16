<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';

$db = new Database();
$conn = $db->getConnection();
redirectIfNotAdmin($conn);

$order = new Order($conn);
$message = '';
$statuses = ['Processing', 'Shipped', 'Delivered', 'Cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();

    $order_id = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($order->updateOrderStatus($order_id, $status)) {
        $message = 'Order status updated.';
    } else {
        $message = 'Order status could not be updated.';
    }
}

$orders = $order->getAllOrders();
?>

<?php include 'includes/header.php'; ?>

<main class="margin70 marginbottom30">
    <section class="admin-panel">
        <div class="admin-hero">
            <div>
                <p class="admin-eyebrow">Admin Orders</p>
                <h1>Manage Orders</h1>
                <p>View customer orders, update fulfillment status, and open invoice details.</p>
            </div>
            <div class="admin-summary">
                <span><?= count($orders) ?></span>
                <small>Total Orders</small>
            </div>
            <a href="admin.php" class="admin-add-button text-none">Back to Products</a>
        </div>

        <?php if ($message): ?>
            <p class="admin-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="tblProducts tblOrders">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="7">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $item): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($item['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($item['customer']) ?></strong><br>
                                    <span><?= htmlspecialchars($item['customer_email']) ?></span>
                                </td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($item['created_at']))) ?></td>
                                <td><?= htmlspecialchars($item['total_items']) ?></td>
                                <td>$<?= number_format($item['total_price'], 2) ?></td>
                                <td>
                                    <form method="post" class="admin-status-form">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($item['id']) ?>">
                                        <select name="status">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= htmlspecialchars($status) ?>" <?= $item['order_status'] === $status ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($status) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="button">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="adminOrderDetails.php?order_id=<?= htmlspecialchars($item['id']) ?>" class="vbutton text-none">View</a>
                                        <a href="pdfCheckout.php?order_id=<?= htmlspecialchars($item['id']) ?>&download=1&admin=1" class="button text-none">Invoice</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
