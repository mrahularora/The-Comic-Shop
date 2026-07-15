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

$msg = $name = $description = $long_description = $price = $category_id = "";
$errors = [];
$image_name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $product_id = htmlspecialchars($_POST["product_id"]);
     $current_image = htmlspecialchars($_POST["current_image"]);

     if (!empty($_POST["name"])) {
          $name = $_POST["name"];
     } else {
          $errors[] = "Name is required";
     }

     if (!empty($_POST["description"])) {
          $description = $_POST["description"];
     } else {
          $errors[] = "Description is required";
     }

     if (!empty($_POST["long_description"])) {
          $long_description = $_POST["long_description"];
     } else {
          $errors[] = "Long description is required";
     }

     if (!empty($_POST["price"])) {
          $price = $_POST["price"];
          if (!preg_match("/^\d{1,6}(\.\d{2})?$/", $price)) {
               $errors[] = "Price must be numeric (max 999999.99)";
          }
     } else {
          $errors[] = "Price is required";
     }

     if (!empty($_POST["category_id"])) {
          $category_id = $_POST["category_id"];
          if (!preg_match("/^\d+$/", $category_id)) {
               $errors[] = "Category ID must be numeric";
          }
     } else {
          $errors[] = "Category ID is required";
     }

     if (!empty($_FILES["productImage"]["name"])) {
          $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
          $fileType = finfo_file($fileInfo, $_FILES["productImage"]["tmp_name"]);

          if (in_array($fileType, ["image/jpeg", "image/webp", "image/png", "image/x-ms-bmp"])) {
               $ext = explode("/", $fileType)[1];
               $newFileName = "images/products/" . uniqid("upload_", true) . ".$ext";

               if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $newFileName)) {
                    $image_name = $newFileName;
               } else {
                    $errors[] = "Failed to move uploaded file.";
               }
          } else {
               $errors[] = "Invalid image file type";
          }
     } else {
          $image_name = $current_image;
     }

     if (count($errors) == 0) {
          $res = $objProduct->updateProduct($product_id, $name, $description, $long_description, $price, $image_name, $category_id);

          if ($res) {
               header("Location: admin.php");
               exit;
          } else {
               $msg = "<h5>Product couldn't be edited</h5><br/>";
          }
          $msg .= "<a href='admin.php'><button>OK</button></a>";
     }
} else {
     $product_id = $_GET["product_id"];
     $product = $objProduct->getProductById($product_id);

     if ($product) {
          $name = $product["name"];
          $description = $product["description"];
          $long_description = $product["long_description"];
          $price = $product["price"];
          $category_id = $product["category_id"];
          $image_name = $product["image"];
     }
}
?>

<?php include 'includes/header.php'; ?>

<!-- Main Content -->

<main class="mid75">
    <section class="marginbottom30 margin70">
     <a href="admin.php" class="button text-none"><< Back</a><br /><br />
               <h3>Edit Product</h3>

               <form action="editProduct.php" method="POST" enctype="multipart/form-data">

               <div class="form-group">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>" />
                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($image_name) ?>" />
               </div>
               
               <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" />
               </div>

               <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" value="<?= htmlspecialchars($description) ?>" />
               </div>

               <div class="form-group">
                    <label for="long_description">Long Description</label>
                    <textarea name="long_description" rows="6" cols="150"><?= htmlspecialchars($long_description) ?></textarea>
               </div>

               <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" name="price" value="<?= htmlspecialchars($price) ?>" />
               </div>

               <div class="form-group">
                    <label for="category_id">Category ID</label>
                    <select name="category_id">
                         <option value=""> -- Select -- </option>
                         <?php foreach ($categories as $category): ?>
                              <option value="<?= htmlspecialchars($category['id']) ?>"
                                   <?= $category_id == $category['id'] ? 'selected' : '' ?>>
                                   <?= htmlspecialchars($category['name']) ?>
                              </option>
                         <?php endforeach; ?>
                    </select>
               </div>
               
               <div class="form-group">
                    <label for="category_id">Image</label>
                    <div>
                         <label class="upload-button" for="productImage">
                              <img src="<?= htmlspecialchars($image_name) ?>" alt="Upload Image">
                         </label>
                         <input id="productImage" name="productImage" type="file" />
                    </div>
                    <span id="file-name"></span>
                    <?php
                    if ($msg == "") {
                    ?>
                         <div>
                              <button type="submit" class="button">Save Edit</button>
                         </div>
                    <?php
                    } else {
                         foreach ($errors as $error)
                              echo "<p class='error'>$error<p>";
                    }
                    ?>
               </div>
               </form>
               <?php
               foreach ($errors as $error)
                    echo '<p class="error">' . $error . '</p><br/>';

               echo $msg
               ?>

</section>

</main>

<?php include 'includes/footer.php'; ?>
