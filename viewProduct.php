<?php
include 'session_start.php';
include_once 'database.php';
include_once 'classes.php';
include_once 'functions.php';

$db = new Database();
$getprod = new ProductItem($db->getConnection());
$cart = new ShoppingCart();

if (isset($_GET['id'])) {
    $prod = $getprod->getProductById($_GET['id']);
} else {
    return redirect();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart->addToCart($_POST['product_id'], $_POST['quantity']);
    header('Location: cart.php');
    exit();
}
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
                <p class="product-price"><strong>Price: $<?= number_format($prod['price'], 2) ?></strong></p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                    <div class="quantity-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1">
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
            <form action="subscribe" method="post">
                <input type="email" name="email" placeholder="Enter your email address" required>
                <input type="submit" value="Subscribe">
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
