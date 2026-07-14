<?php
include_once 'config/database.php';

$db = new Database();
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
?>

<?php include 'includes/header.php'; ?>

<main class="mid75">
    <section class="margin70 center"><br /><br /><br /><br />
        <h1>Thank You for Your Order!</h1><br /><br />
        <p>Your order ID is <?= htmlspecialchars($order_id) ?>. We will process it shortly.</p><br />
        <a href="pdf/order_details_<?= $order_id ?>.pdf" target="_blank" class="button text-none">Download Invoice</a>
        <a href="index.php" class="vbutton text-none">Return to Home</a>
    </section>
</main>


<?php include 'includes/footer.php'; ?>