<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';
redirectIfNotLoggedIn();

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$db = new Database();
$order = new Order($db->getConnection());
$orderDetails = $order->getOrderDetails($order_id, $_SESSION['user_id']);

if (empty($orderDetails)) {
    header('Location: orders.php');
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<main class="mid75">
    <section class="margin70 center"><br /><br /><br /><br />
        <h1>Thank You for Your Order!</h1><br /><br />
        <p>Your order ID is <?= htmlspecialchars($order_id) ?>. We will process it shortly.</p><br />
        <a href="orderDetails.php?order_id=<?= htmlspecialchars($order_id) ?>" class="button text-none">Track Order</a>
        <a href="pdfCheckout.php?order_id=<?= htmlspecialchars($order_id) ?>&download=1" class="button text-none">Download Invoice</a>
        <a href="index.php" class="vbutton text-none">Return to Home</a>
    </section>
</main>


<?php include 'includes/footer.php'; ?>
