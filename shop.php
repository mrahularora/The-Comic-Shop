<?php
include 'includes/session_start.php';
include_once 'config/database.php';
include_once 'includes/classes.php';
include_once 'includes/functions.php';

$db = new Database();
$getprod = new ProductItem($db->getConnection());
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
// Fetch products based on the selected sorting option
$products = $getprod->getProducts($sort);
$cart = new ShoppingCart(); 

if (isset($_GET['id'])) {
    $prod = $getprod->getProductById($_GET['id']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart->addToCart($_POST['product_id'], 1);
    header('Location: cart.php');
    exit();
}
?>
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
     <main>
    <h1 class="margin70 center">Our Comic Books</h1>
            <p class="center">Browse our collection of comic books.</p>
  
    <section class="mid80">
    <h1><img src="images/icons/marvel.png" class="width35" /> All Collection</h1><br />
    <div class="sort-options">
            <form method="get" action="">
                <label for="sort" class="sort bold">Sort by:</label>
                <select class="sort" name="sort" id="sort" onchange="this.form.submit()">
                    <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name - A-Z</option>
                    <option value="price" <?php echo $sort === 'price' ? 'selected' : ''; ?>>Price - Low to High</option>
                </select>
            </form>
        </div>
        <div class="clearfix"></div>
        <div class="productgrid">
        <?php

        if (count($products) > 0) {
            foreach ($products as $product) {
                $pid = (int) $product['id'];
                $pname = htmlspecialchars($product["name"]);
                $pdescription = htmlspecialchars($product["description"]);
                $pprice = htmlspecialchars($product["price"]);
                $pimage = htmlspecialchars($product["image"]);

                echo '
                <div class="product">
                <form method="post">
                <input type="hidden" name="product_id" value="'.$pid.'">
                <a href="viewProduct.php?id='.$pid.'">
                <img src="'.$pimage.'" alt="Product.'.$pname.'" width="100%" class="scale" />
                </a>
                <p class="pname">'.$pname.'</p> 
                <p class="desc">'. $pdescription.'<br /><br />
                <p class="price">$'.$pprice.'</p>
                <div><button class="button">Add to Cart</button>
                <a href="viewProduct.php?id='.$pid.'" class="vbutton">View Comic</a></div>
                </form>
                </div>
                
                ';
            }
        } else {
            echo "No products found.";
        }
        ?>
        </div>
        <div class="clearfix"></div>

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
