<?php
include 'session_start.php';
include_once 'classes.php';
include_once 'database.php';

$db = new Database();
$product = new ProductItem($db->getConnection()); 
$cart = new ShoppingCart(); 

$error_message = '';

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'update') {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        
        if ($quantity > 5) {
            $error_message = 'You cannot update the quantity to more than 5 units.';
        } else {
            $cart->updateQuantity($product_id, $quantity);
        }
    } elseif ($_POST['action'] === 'remove') {
        $cart->removeItem($_POST['product_id']);
    } elseif ($_POST['action'] === 'empty') {
        $cart->emptyCart();
    }
}

$cart_items = $cart->getCart();
$is_cart_empty = empty($cart_items); // Check if the cart is empty

// Calculate subtotal, tax, and total
$subtotal = 0;
$total_items = 0; // Initialize total items count
foreach ($cart_items as $product_id => $quantity) {
    $prod = $product->getProductById($product_id);
    if (!$prod) {
        $cart->removeItem($product_id);
        continue;
    }
    $subtotal += $prod['price'] * $quantity;
    $total_items += $quantity; // Count total items
}
$cart_items = $cart->getCart();
$is_cart_empty = empty($cart_items);
$tax_rate = 0.13; // 13%
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;
?>

<?php include 'includes/header.php'; ?>

<main class="margin70 marginbottom30">

<section class="mid80 marginauto">
    <h1><img src="images/icons/cart-black.png" class="width25" /> Shopping Cart (<?= htmlspecialchars($total_items) ?> items)</h1>

    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($is_cart_empty): ?>
        <div class="error margin70"><p>Your cart is empty. Please add items to your cart before proceeding.<br /><br /><a href="shop.php" class="button text-none">View Shop</a></p></div>
    <?php else: ?>
        <div class="right">
            <form action="" method="post" class="inline-form">
                <input type="hidden" name="action" value="empty">
                <button type="submit" class="button">Empty Cart</button>
            </form>

            <a href="checkout.php" class="vbutton">Proceed to Checkout</a>
            <a href="shop.php" class="continuebutton">Continue Shopping</a>
        </div>
        <div class="clearfix"></div>

        <ul class="cart-list">
            <?php
            foreach ($cart_items as $product_id => $quantity) {
                $prod = $product->getProductById($product_id);
                if (!$prod) {
                    continue;
                }
                $item_total = $prod['price'] * $quantity;
            ?>
            
                <li class="cart-item">
                <a href="viewProduct.php?id=<?= htmlspecialchars($product_id) ?>"><img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="scale" /></a>
                    <div class="details">
                        <p class="name bold"><?= htmlspecialchars($prod['name']) ?></p>
                        <p class="description"><?= htmlspecialchars($prod['description']) ?></p><br />
                        <p class="price bold"><?= htmlspecialchars($prod['price']) ?> each</p><br />
                        <p class="quantity">Quantity : <span class="quantity"><?= htmlspecialchars($quantity) ?></span></p><br />

                            <!-- Update Quantity Form -->
                            <form action="" method="post" class="inline-form">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                            <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>" min="1" max="5" class="input-quantity">
                            <input type="hidden" name="action" value="update">
                            <button type="submit" class="ubutton">Update</button>
                        </form>

                         <!-- Remove Product Form -->
                         <form action="" method="post" class="inline-form">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="button">Remove</button>
                        </form>
                    </div>
                    <div class="actions"><p class="total">= $<?= number_format($item_total, 2) ?></p></div>
                </li>
            <?php } ?>
        </ul>

        <div class="cart-summary margin30 right">
            <p class="subtotal bold text-right">Subtotal: $<?= number_format($subtotal, 2) ?></p>
            <p class="tax bold text-right">Tax (13%): $<?= number_format($tax, 2) ?></p>
            <p class="total bold text-right">Total: $<?= number_format($total, 2) ?></p><br />

            <!-- Empty Cart Form -->
            <form action="" method="post" class="inline-form">
                <input type="hidden" name="action" value="empty">
                <button type="submit" class="button">Empty Cart</button>
            </form>

            <a href="checkout.php" class="vbutton">Proceed to Checkout</a>
            <a href="shop.php" class="continuebutton">Continue Shopping</a>
        </div>
        <div class="clearfix"></div>
    <?php endif; ?>

</section>

</main>

<?php include 'includes/footer.php'; ?>
