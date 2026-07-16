<?php
include 'includes/session_start.php';
include_once 'config/database.php';
include_once 'includes/classes.php';
include_once 'includes/functions.php';

$name = $description = $long_description = $price = $category_id = $publisher = $writer = $format = $age_rating = "";
$errors = [];
$image_name = "";

$db = new Database();
$conn = $db->getConnection();
redirectIfNotAdmin($conn);
$objProducts = new ProductItem($conn);
$objCategories = new Categories($conn);
$categories = $objCategories->getCategories();
$categoryIds = array_map('intval', array_column($categories, 'id'));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     verify_csrf_token();

     $name = trim($_POST["name"] ?? "");
     $description = trim($_POST["description"] ?? "");
     $long_description = trim($_POST["long_description"] ?? "");
     $publisher = trim($_POST["publisher"] ?? "");
     $writer = trim($_POST["writer"] ?? "");
     $format = trim($_POST["format"] ?? "");
     $age_rating = trim($_POST["age_rating"] ?? "");
     $price = trim($_POST["price"] ?? "");
     $category_id = (int) ($_POST["category_id"] ?? 0);

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

     if (empty($_FILES["productImage"]["name"])) {
          $errors[] = "Cover image is required.";
     } elseif ($_FILES["productImage"]["error"] !== UPLOAD_ERR_OK) {
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

     if (empty($errors)) {
          $objProducts->addProduct($name, $description, $long_description, $price, $image_name, $category_id, $publisher, $writer, $format, $age_rating);
          header("Location: admin.php");
          exit;
     }
}
?>

<?php include 'includes/header.php'; ?>

<main class="margin70 marginbottom30">
     <section class="admin-panel admin-form-page">
          <div class="admin-hero">
               <div>
                    <p class="admin-eyebrow">Admin Catalog</p>
                    <h1>Add Comic Book</h1>
                    <p>Create a new catalog item with pricing, category, story details, and cover image.</p>
               </div>
               <div class="admin-hero-actions">
                    <a href="admin.php" class="admin-add-button text-none">Back to Products</a>
                    <a href="editProduct.php" class="admin-add-button text-none">Edit Products</a>
               </div>
          </div>

          <?php foreach ($errors as $error): ?>
               <p class="admin-message"><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>

          <form class="admin-form-card" action="addProduct.php" method="POST" enctype="multipart/form-data">
               <?= csrf_field() ?>

               <div class="admin-form-grid">
                    <div class="form-group">
                         <label for="name">Comic Name / Title <span class="red">*</span></label>
                         <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                    </div>

                    <div class="form-group">
                         <label for="price">Price <span class="red">*</span></label>
                         <input type="text" id="price" name="price" value="<?= htmlspecialchars($price) ?>" placeholder="19.99" required>
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
                    <label for="productImage">Cover Image <span class="red">*</span></label>
                    <div class="admin-image-preview">
                         <img src="images/uploadImage.svg" alt="Upload cover image">
                         <div>
                              <p>Upload JPG, PNG, WebP, or BMP. Max size: 2MB.</p>
                              <label class="admin-add-button text-none" for="productImage">Choose Image</label>
                              <input id="productImage" name="productImage" type="file" accept="image/jpeg,image/png,image/webp,image/bmp" required>
                              <span id="file-name"></span>
                         </div>
                    </div>
               </div>

               <div class="admin-hero-actions">
                    <button type="submit" class="admin-add-button">Add Product</button>
                    <a href="admin.php" class="continuebutton text-none">Cancel</a>
               </div>
          </form>
     </section>
</main>

<?php include 'includes/footer.php'; ?>
