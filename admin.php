<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';

$db = new Database();
$conn = $db->getConnection();
redirectIfNotAdmin($conn);
$objProduct = new ProductItem($conn);
$objOrder = new Order($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

     if (isset($_POST['product_id'])) {
          $res = $objProduct->deleteProduct(htmlspecialchars($_POST['product_id']));

          if ($res)
               echo "<p class='info'>The product was successfully deleted.</p>";
     }
}

$products = $objProduct->getStaticProducts();
$productCount = count($products);
$orderCount = $objOrder->getOrderCount();

?>
<?php include 'includes/header.php'; ?>

     <main class="margin70 marginbottom30">
     <section class="admin-panel">

          <div class="admin-hero">
               <div>
                    <p class="admin-eyebrow">Admin Panel</p>
                    <h1>Manage Comic Books</h1>
                    <p>Review, edit, and remove catalog items from one place.</p>
               </div>
               <div class="admin-summary">
                    <span><?= $productCount ?></span>
                    <small>Total Products</small>
               </div>
               <div class="admin-summary">
                    <span><?= $orderCount ?></span>
                    <small>Total Orders</small>
               </div>
               <div class="admin-hero-actions">
                    <a href="adminOrders.php" class="admin-add-button text-none">Manage Orders</a>
                    <a href="addProduct.php" class="admin-add-button text-none">New Product / Comic Book</a>
               </div>
          </div>

          <div class="admin-table-wrap">
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
                              $pname = htmlspecialchars($product["name"]);
                              $pdescription = htmlspecialchars($product["description"]);
                              $plongdescription = htmlspecialchars($product["long_description"]);
                              $pprice = htmlspecialchars($product["price"]);
                              $pimage = htmlspecialchars($product["image"]);
                              $pid = (int) $product['id'];

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
          </div>
     </section>
     </main>

     <?php include 'includes/footer.php'; ?>
