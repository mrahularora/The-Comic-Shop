<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';

$db = new Database();
$conn = $db->getConnection();
redirectIfNotAdmin($conn);
$objProduct = new ProductItem($conn);
$objCategories = new Categories($conn);

$categories = $objCategories->getCategories();
$categoryIds = array_map('intval', array_column($categories, 'id'));
$categoryNames = array_column($categories, 'name', 'id');
$products = $objProduct->getStaticProducts();

$msg = $name = $description = $long_description = $price = $category_id = $publisher = $writer = $format = $age_rating = "";
$errors = [];
$image_name = "";
$product_id = 0;
$showPicker = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     verify_csrf_token();

     $product_id = (int) ($_POST["product_id"] ?? 0);
     $currentProduct = $objProduct->getProductById($product_id);
     if (!$currentProduct) {
          $errors[] = "Product not found.";
          $showPicker = true;
     } else {
          $name = trim($_POST["name"] ?? "");
          $description = trim($_POST["description"] ?? "");
          $long_description = trim($_POST["long_description"] ?? "");
          $publisher = trim($_POST["publisher"] ?? "");
          $writer = trim($_POST["writer"] ?? "");
          $format = trim($_POST["format"] ?? "");
          $age_rating = trim($_POST["age_rating"] ?? "");
          $price = trim($_POST["price"] ?? "");
          $category_id = (int) ($_POST["category_id"] ?? 0);
          $image_name = $currentProduct["image"];

          if ($name === "") {
               $errors[] = "Name is required.";
          }

          if ($description === "") {
               $errors[] = "Description is required.";
          }

          if ($long_description === "") {
               $errors[] = "Long description is required.";
          }

          if (!preg_match("/^\d{1,6}(\.\d{2})?$/", $price)) {
               $errors[] = "Price must be numeric, like 19.99.";
          }

          if (!in_array($category_id, $categoryIds, true)) {
               $errors[] = "Please select a valid category.";
          }

          if (!empty($_FILES["productImage"]["name"])) {
               if ($_FILES["productImage"]["error"] !== UPLOAD_ERR_OK) {
                    $errors[] = "Image upload failed.";
               } elseif ($_FILES["productImage"]["size"] > 2 * 1024 * 1024) {
                    $errors[] = "Image must be 2MB or smaller.";
               } else {
                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $fileType = finfo_file($fileInfo, $_FILES["productImage"]["tmp_name"]);
                    finfo_close($fileInfo);
                    $allowedTypes = [
                         "image/jpeg" => "jpg",
                         "image/webp" => "webp",
                         "image/png" => "png",
                         "image/x-ms-bmp" => "bmp",
                    ];

                    if (isset($allowedTypes[$fileType])) {
                         $ext = $allowedTypes[$fileType];
                         $newFileName = "images/products/" . uniqid("upload_", true) . ".$ext";

                         if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $newFileName)) {
                              $image_name = $newFileName;
                         } else {
                              $errors[] = "Failed to move uploaded file.";
                         }
                    } else {
                         $errors[] = "Invalid image file type.";
                    }
               }
          }

          if (empty($errors)) {
               $objProduct->updateProduct($product_id, $name, $description, $long_description, $price, $image_name, $category_id, $publisher, $writer, $format, $age_rating);
               header("Location: admin.php");
               exit;
          }
     }
} else {
     $product_id = (int) ($_GET["product_id"] ?? 0);
     if ($product_id > 0) {
          $product = $objProduct->getProductById($product_id);
          if ($product) {
               $name = $product["name"];
               $description = $product["description"];
               $long_description = $product["long_description"];
               $price = $product["price"];
               $category_id = $product["category_id"];
               $image_name = $product["image"];
               $publisher = $product["publisher"] ?? "";
               $writer = $product["writer"] ?? "";
               $format = $product["format"] ?? "";
               $age_rating = $product["age_rating"] ?? "";
          } else {
               $errors[] = "Product not found.";
               $showPicker = true;
          }
     } else {
          $showPicker = true;
     }
}
?>

<?php include 'includes/header.php'; ?>

<main class="margin70 marginbottom30">
     <section class="admin-panel admin-form-page">
          <div class="admin-hero">
               <div>
                    <p class="admin-eyebrow">Admin Editor</p>
                    <h1>Edit Comic Book</h1>
                    <p>Select a comic to edit its catalog details, metadata, price, category, and cover image.</p>
               </div>
               <div class="admin-hero-actions">
                    <a href="admin.php" class="admin-add-button text-none">Back to Products</a>
                    <a href="addProduct.php" class="admin-add-button text-none">New Product</a>
               </div>
          </div>

          <?php foreach ($errors as $error): ?>
               <p class="admin-message"><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>

          <?php if ($showPicker): ?>
               <div class="admin-table-wrap">
                    <table class="tblProducts admin-product-picker">
                         <thead>
                              <tr>
                                   <th>Image</th>
                                   <th>Name</th>
                                   <th>Category</th>
                                   <th>Price</th>
                                   <th>Action</th>
                              </tr>
                         </thead>
                         <tbody>
                              <?php if (empty($products)): ?>
                                   <tr><td colspan="5">No products found.</td></tr>
                              <?php else: ?>
                                   <?php foreach ($products as $product): ?>
                                        <tr>
                                             <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"></td>
                                             <td><?= htmlspecialchars($product['name']) ?></td>
                                             <td><?= htmlspecialchars($categoryNames[$product['category_id']] ?? $product['category_id']) ?></td>
                                             <td>$<?= htmlspecialchars($product['price']) ?></td>
                                             <td><a href="editProduct.php?product_id=<?= htmlspecialchars($product['id']) ?>" class="vbutton text-none">Edit</a></td>
                                        </tr>
                                   <?php endforeach; ?>
                              <?php endif; ?>
                         </tbody>
                    </table>
               </div>
          <?php else: ?>
               <form action="editProduct.php" method="POST" enctype="multipart/form-data" class="admin-form-card">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

                    <div class="admin-form-grid">
                         <div class="form-group">
                              <label for="name">Comic Name / Title <span class="red">*</span></label>
                              <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                         </div>

                         <div class="form-group">
                              <label for="price">Price <span class="red">*</span></label>
                              <input type="text" id="price" name="price" value="<?= htmlspecialchars($price) ?>" required>
                         </div>

                         <div class="form-group">
                              <label for="category_id">Category <span class="red">*</span></label>
                              <select id="category_id" name="category_id" required>
                                   <option value="">-- Select --</option>
                                   <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id']) ?>" <?= (int) $category_id === (int) $category['id'] ? 'selected' : '' ?>>
                                             <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                   <?php endforeach; ?>
                              </select>
                         </div>

                         <div class="form-group">
                              <label for="publisher">Publisher</label>
                              <input type="text" id="publisher" name="publisher" value="<?= htmlspecialchars($publisher) ?>">
                         </div>

                         <div class="form-group">
                              <label for="writer">Writer</label>
                              <input type="text" id="writer" name="writer" value="<?= htmlspecialchars($writer) ?>">
                         </div>

                         <div class="form-group">
                              <label for="format">Format</label>
                              <input type="text" id="format" name="format" value="<?= htmlspecialchars($format) ?>">
                         </div>

                         <div class="form-group">
                              <label for="age_rating">Age Rating</label>
                              <input type="text" id="age_rating" name="age_rating" value="<?= htmlspecialchars($age_rating) ?>">
                         </div>
                    </div>

                    <div class="form-group">
                         <label for="description">Short Description <span class="red">*</span></label>
                         <textarea id="description" name="description" rows="3" required><?= htmlspecialchars($description) ?></textarea>
                    </div>

                    <div class="form-group">
                         <label for="long_description">Long Description <span class="red">*</span></label>
                         <textarea id="long_description" name="long_description" rows="7" required><?= htmlspecialchars($long_description) ?></textarea>
                    </div>

                    <div class="form-group">
                         <label for="productImage">Cover Image</label>
                         <div class="admin-image-preview">
                              <img src="<?= htmlspecialchars($image_name) ?>" alt="<?= htmlspecialchars($name) ?>">
                              <div>
                                   <p>Current cover image</p>
                                   <label class="admin-add-button text-none" for="productImage">Choose New Image</label>
                                   <input id="productImage" name="productImage" type="file" accept="image/jpeg,image/png,image/webp,image/bmp">
                                   <span id="file-name"></span>
                              </div>
                         </div>
                    </div>

                    <div class="admin-hero-actions">
                         <button type="submit" class="admin-add-button">Save Changes</button>
                         <a href="admin.php" class="continuebutton text-none">Cancel</a>
                    </div>
               </form>
          <?php endif; ?>
     </section>
</main>

<?php include 'includes/footer.php'; ?>
