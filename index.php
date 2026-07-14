<?php
include 'session_start.php';
include_once 'database.php';
include_once 'classes.php';


if($_SERVER["REQUEST_METHOD"]=="POST")
    {
        unset($_COOKIE["visited"]);
        setcookie("visited",0,time() - 3600,"/");
    }

    if(empty($_COOKIE["visited"]))
        setcookie("visited","1",time() + 10,"/");
    else
        setcookie("visited",((int)$_COOKIE["visited"]) + 1,time() + 10,"/");
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

$db = new Database();
$getprod = new ProductItem($db->getConnection());
$products = $getprod->fetchMarvelProducts();
$dcproducts = $getprod->fetchDcProducts();
$otherproducts = $getprod->fetchOtherProducts();
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

<main>
    <section>
        <div class="slideshow-container">
            <div class="mySlides fade">
                <img src="images/spidy.jpg" style="width:100%">
            </div>
            <div class="mySlides fade">
                <img src="images/wol.jpg" style="width:100%">
            </div>
            <div class="mySlides fade">
                <img src="images/batman.jpg" style="width:100%">
            </div>
            <div class="mySlides fade">
                <img src="images/iron.jpg" style="width:100%">
            </div>
        </div>
    </section>

    <section class="mid80">
        <h1 class="center margin30">Welcome to the Comic Book Shop!</h1>
        <p class="center">
            <i>Your one-stop shop for all things comic books.</i><br /><br />
            Step into a heroic, villainous, and epic tale of adventure stories. Welcome to Comic Book Shop!, where comic book lovers come to 
            get all things comic books. Whether you are a seasoned collector, casual reader, or beginning explorer into the realm of 
            comics—we have something for everyone.
        </p><br />

        <h1><img src="images/icons/iron.png" class="width35" /> Latest Marvel Comic Books</h1>
        <div class="productgrid">
        <?php
        if (count($products) > 0) {
            foreach ($products as $product) {
                $pname = $product["name"];
                $pdescription = $product["description"];
                $pprice = $product["price"];
                $pimage = $product["image"];

                echo '
                <div class="product">
                    <form method="post">
                        <input type="hidden" name="product_id" value="'.$product['id'].'">
                        <img src="'.$pimage.'" width="100%" alt="Product.'.$pname.'" class="scale" />
                        <p class="pname">'.$pname.'</p> 
                        <p class="desc">'.$pdescription.'</p>
                        <p class="price">$'.$pprice.'</p>
                        <div>
                            <button class="button">Add to Cart</button>
                            <a href="viewProduct.php?id='.$product['id'].'" class="vbutton">View Comic</a>
                        </div>
                    </form>
                </div>';
            }
        } else {
            echo "No products found.";
        }
        ?>
        </div>
        <div class="clearfix"></div>
    </section>

    <section class="margin50">
        <div class="textoverimage skew-container">
            <img src="images/vs.jpg" alt="Marvel VS DC">
            <div class="text">
                <h1>Marvel VS DC</h1>
                <p>The large-scale battle of the human heroes and the iconic written heroes keep entertaining mankind. Marvel and DC Comics – eternal rivals. On one hand, there are characters like Spider-Man, Iron Man, and Avengers or Universe filled with dynamic and important characters. On the other hand, there is DC Comics with its timeless heroes Batman, Superman, and Wonder Woman, which has shared with mankind the rich tapestry of heroic legends and dark, thrilling tales.</p>
            </div>
        </div>
    </section>

    <section class="mid80">
        <h1 class="margin100"><img src="images/icons/bat.png" class="width35" /> Latest DC Comic Books</h1>
        <div class="productgrid">
        <?php
        if (count($dcproducts) > 0) {
            foreach ($dcproducts as $dcproduct) {
                $dcpname = $dcproduct["name"];
                $dcpdescription = $dcproduct["description"];
                $dcpprice = $dcproduct["price"];
                $dcpimage = $dcproduct["image"];

                echo '
                <div class="product">
                    <form method="post">
                        <input type="hidden" name="product_id" value="'.$dcproduct['id'].'">
                        <img src="'.$dcpimage.'" width="100%"  alt="Product.'.$dcpname.'" class="scale" />
                        <p class="pname">'.$dcpname.'</p> 
                        <p class="desc">'.$dcpdescription.'</p>
                        <p class="price">$'.$dcpprice.'</p>
                        <div>
                            <button class="button">Add to Cart</button>
                            <a href="viewProduct.php?id='.$dcproduct['id'].'" class="vbutton">View Comic</a>
                        </div>
                    </form>
                </div>';
            }
        } else {
            echo "No products found.";
        }
        ?>
        </div>
        <div class="clearfix"></div>
    </section>


    <section class="mid80">
        <h1 class="margin50"><img src="images/icons/other.png" class="width35" /> Other Comic Books</h1>
        <div class="productgrid">
        <?php
        if (count($otherproducts) > 0) {
            foreach ($otherproducts as $otherproduct) {
                $otherpname = $otherproduct["name"];
                $otherpdescription = $otherproduct["description"];
                $otherpprice = $otherproduct["price"];
                $otherpimage = $otherproduct["image"];

                echo '
                <div class="product">
                    <form method="post">
                        <input type="hidden" name="product_id" value="'.$otherproduct['id'].'">
                        <img src="'.$otherpimage.'" width="100%" alt="Product.'.$otherpname.'" class="scale" />
                        <p class="pname">'.$otherpname.'</p> 
                        <p class="desc">'.$otherpdescription.'</p>
                        <p class="price">$'.$otherpprice.'</p>
                        <div>
                            <button class="button">Add to Cart</button>
                            <a href="viewProduct.php?id='.$otherproduct['id'].'" class="vbutton">View Comic</a>
                        </div>
                    </form>
                </div>';
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
