<?php
include 'session_start.php';
include_once 'classes.php';
include_once 'database.php';
include_once 'functions.php';
redirectIfNotLoggedIn();

$db = new Database();
$objProduct = new ProductItem($db->getConnection());

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     if (isset($_POST['product_id'])) {
          $res = $objProduct->deleteProduct(htmlspecialchars($_POST['product_id']));

          if ($res)
               echo "<p class='info'>The product was successfully deleted.</p>";
     }
}

$products = $objProduct->getStaticProducts();

?>
<?php include 'includes/header.php'; ?>

     <main class="margin70 marginbottom30">
     <section class="mid95">

          <h1>Welcome to the Admin Panel</h1><br />
          <p>Hi! Admin, Welcome</p><br />

          <a href="addProduct.php" class="vbutton text-none">New Product / Comic Book</a><br /><br />

          <table class="tblProducts">
               <thead>
                    <tr>
                         <th>Image</th>
                         <th>Name</th>
                         <th>Description</th>
                         <th>Long Description</th>
                         <th>Price</th>
                         <th>Actions</th>
                    </tr>
               </thead>
               <tbody>
                    <?php
                    if (!empty($products)) {
                         foreach ($products as $product) {
                              $pname = $product["name"];
                              $pdescription = $product["description"];
                              $plongdescription = $product["long_description"];
                              $pprice = $product["price"];
                              $pimage = "" . $product["image"];
                              $pid = $product['id'];

                              echo '<tr>
                                        <td><img src="' . $pimage . '" alt="' . $pname . '"></td>
                                        <td>' . $pname . '</td>
                                        <td>' . $pdescription . '</td>
                                        <td>' . $plongdescription . '</td>
                                        <td>$' . $pprice . '</td>
                                        <td>
                                             <div class="action-buttons">
                                                  <form method="GET" action="editProduct.php" style="display:inline-block;">
                                                       <input type="hidden" name="product_id" value="' . $pid . '">
                                                       <button class="button edit-button" type="submit" name="action" value="edit">
                                                       <i class="fas fa-edit"></i>
                                                       </button>
                                                  </form>

                                                  <form method="POST" action="admin.php" style="display:inline-block;">
                                                       <input type="hidden" name="product_id" value="' . $pid . '">
                                                       <button class="button delete-button" type="submit" name="action" value="delete">
                                                       <i class="fas fa-trash-alt"></i>
                                                       </button>
                                                  </form>
                                             </div>
                                        </td>
                                   </tr>';
                         }
                    } else {
                         echo '
                    <tr>
                        <td colspan="5">No products found.</td>
                    </tr>';
                    }
                    ?>
               </tbody>
          </table>
     </section>
     </main>

     <?php include 'includes/footer.php'; ?>
