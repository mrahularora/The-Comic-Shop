<?php
include 'includes/session_start.php';
include_once 'config/database.php';
include_once 'includes/classes.php';

$db = new Database();
$getprod = new ProductItem($db->getConnection());
$sortOptions = [
    'name_asc' => 'Name - A-Z',
    'name_desc' => 'Name - Z-A',
    'price_asc' => 'Price - Low to High',
    'price_desc' => 'Price - High to Low',
    'newest' => 'Newest First',
    'oldest' => 'Oldest First',
    'category' => 'Category',
];
$sort = array_key_exists($_GET['sort'] ?? '', $sortOptions) ? $_GET['sort'] : 'name_asc';
$products = $getprod->getProducts($sort);
$productCount = count($products);
$cart = new ShoppingCart(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();

    $_SESSION['cart_message'] = $cart->addToCart($_POST['product_id'], 1) === 'success'
        ? 'Comic added to your cart.'
        : 'This comic could not be added. Maximum quantity is 5.';
    header('Location: shop.php?sort=' . urlencode($sort));
    exit();
}
?>
    <?php include 'includes/header.php'; ?>

    <main class="shop-page">
    <section class="shop-hero mid80">
        <p class="eyebrow">Browse the collection</p>
        <h1>Our Comic Books</h1>
        <p>Find Marvel, DC, and other comic picks in one catalog.</p>
    </section>

    <section class="mid80">
        <div class="shop-toolbar">
            <div>
                <h2><img src="images/icons/marvel.png" class="width35" alt="" /> All Collection</h2>
                <p><?= htmlspecialchars($productCount) ?> comics available</p>
            </div>

            <form method="get" action="shop.php" class="sort-options">
                <label for="sort" class="bold">Sort by</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                        <?php foreach ($sortOptions as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= $sort === $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
        </div>

        <div class="productgrid">
        <?php

        if (count($products) > 0) {
            foreach ($products as $product) {
                $pid = (int) $product['id'];
                $pname = htmlspecialchars($product["name"]);
                $pdescription = htmlspecialchars($product["description"]);
                $pprice = number_format((float) $product["price"], 2);
                $pimage = htmlspecialchars($product["image"]);

                echo '
                <div class="product">
                <form method="post">
                '.csrf_field().'
                <input type="hidden" name="product_id" value="'.$pid.'">
                <a href="viewProduct.php?id='.$pid.'">
                <img src="'.$pimage.'" alt="'.$pname.'" width="100%" class="scale" />
                </a>
                <p class="pname"><a href="viewProduct.php?id='.$pid.'" class="none">'.$pname.'</a></p> 
                <p class="desc">'. $pdescription.'</p>
                <p class="price">$'.$pprice.'</p>
                <div><button class="button">Add to Cart</button>
                <a href="viewProduct.php?id='.$pid.'" class="vbutton">View Comic</a></div>
                </form>
                </div>
                
                ';
            }
        } else {
            echo '<div class="error"><p>No products found.</p></div>';
        }
        ?>
        </div>

    </section>

    <section class="margin30">
        <div class="newsletter">
            <h2>Join the Comic Book Shop Community!</h2>
            <p>Get the latest news, releases, and exclusive content delivered right to your inbox.</p>
            <form action="subscribe.php" method="post">
                <?= csrf_field() ?>
                <input type="email" name="email" placeholder="Log in to use your account email" value="<?= htmlspecialchars($newsletterEmail) ?>" <?= $newsletterEmail ? 'readonly' : '' ?> required>
                <input type="submit" value="Subscribe">
            </form>
        </div>
    </section>
    </main>


    <?php include 'includes/footer.php'; ?>
