<?php
include 'includes/session_start.php';
include_once 'config/database.php';
include_once 'includes/classes.php';
include_once 'includes/functions.php';

$db = new Database();
$getprod = new ProductItem($db->getConnection());
$cart = new ShoppingCart();

if (isset($_GET['id'])) {
    $prod = $getprod->getProductById($_GET['id']);
} else {
    return redirect();
}
if (!$prod) {
    return redirect();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cart_message'] = $cart->addToCart($_POST['product_id'], $_POST['quantity']) === 'success'
        ? 'Comic added to your cart.'
        : 'This comic could not be added. Maximum quantity is 5.';
    header('Location: viewProduct.php?id=' . (int) $_POST['product_id']);
    exit();
}

$categoryNames = [
    1 => 'Marvel Comics',
    2 => 'DC Comics',
    3 => 'Other Comics',
];
$productDetails = [
    'Category' => $categoryNames[(int) ($prod['category_id'] ?? 0)] ?? 'Comics',
    'Publisher' => $prod['publisher'] ?? '',
    'Writer' => $prod['writer'] ?? '',
    'Format' => $prod['format'] ?? '',
    'Age Rating' => $prod['age_rating'] ?? '',
    'Availability' => 'In stock',
    'Order Limit' => '5 copies per order',
];
?>
<?php include 'includes/header.php'; ?>

<!-- Main Content -->
<main class="margin70">
    <section class="mid75"><a href="shop.php" class="button text-none"> &lt;&lt; Back to Shop</a></section><br />
    <section class="product-detail mid75">
        
        <div class="product-content">
            <div class="product-image">
                <img src="<?= htmlspecialchars($prod['image']) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="scale" />
            </div>
            <div class="product-info">
                <h1><?= htmlspecialchars($prod['name']) ?></h1><br />
                <p class="product-description"><b>Description : </b><?= htmlspecialchars($prod['description']) ?></p>
                <p class="product-description"><b>Whats Inside : </b><?= htmlspecialchars($prod['long_description']) ?></p>
                <div class="product-meta">
                    <?php foreach ($productDetails as $label => $value): ?>
                        <div>
                            <span><?= htmlspecialchars($label) ?></span>
                            <strong><?= htmlspecialchars($value ?: 'Not specified') ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="product-price"><strong>Price: $<?= number_format($prod['price'], 2) ?></strong></p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                    <div class="quantity-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="5" value="1">
                    </div>
                    <button type="submit" class="vbutton">Add to Cart</button>
                </form>
            </div>
        </div>
    </section>
    <section class="margin30">
        <div class="newsletter">
            <h2>Join the Comic Book Shop Community!</h2>
            <p>Get the latest news, releases, and exclusive content delivered right to your inbox.</p>
            <form action="subscribe.php" method="post">
                <input type="email" name="email" placeholder="Log in to use your account email" value="<?= htmlspecialchars($newsletterEmail) ?>" <?= $newsletterEmail ? 'readonly' : '' ?> required>
                <input type="submit" value="Subscribe">
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
