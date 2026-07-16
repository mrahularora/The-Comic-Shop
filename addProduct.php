<?php
include 'includes/session_start.php';
include_once 'config/database.php';
include_once 'includes/classes.php';
include_once 'includes/functions.php';

$msg = $name = $description = $long_description = $price = $category_id = $publisher = $writer = $format = $age_rating = "";
$errors = [];
$image_name = "";

$db = new Database();
$conn = $db->getConnection();
redirectIfNotAdmin($conn);
$objProducts = new ProductItem($conn);
$objCategories = new Categories($conn);
$categories = $objCategories->getCategories();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     verify_csrf_token();

     if (!empty($_POST["name"])) {
          $name = trim($_POST["name"]);
     } else {
          $errors[] = "Name is required";
     }

     if (!empty($_POST["description"])) {
          $description = trim($_POST["description"]);
     } else {
          $errors[] = "Description is required";
     }

     if (!empty($_POST["long_description"])) {
          $long_description = trim($_POST["long_description"]);
     } else {
          $errors[] = "Long description is required";
     }

     $publisher = trim($_POST["publisher"] ?? "");
     $writer = trim($_POST["writer"] ?? "");
     $format = trim($_POST["format"] ?? "");
     $age_rating = trim($_POST["age_rating"] ?? "");

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
               $errors[] = "Invalid image file type";
          }
          }
     } else {
          $errors[] = "Image is required";
     }

     if (count($errors) == 0) {
          $objProducts->addProduct($name, $description, $long_description, $price, $image_name, $category_id, $publisher, $writer, $format, $age_rating);

          header("Location: admin.php");
          exit;
     }
}
?>

<?php include 'includes/header.php'; ?>

     <main class="margin40 addProduct-main">
     <section class="margin70 marginbottom30 addProduct-section">
     <div class="addProduct-container">
     <a href="admin.php" class="button text-none"><< Back</a><br /><br />
               <h2 class="center">Add New Comic Book</h2><br />

               <form class="marginauto" action="addProduct.php" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
               
                    <div class="form-group">
                         <label for="name">Comic Name / Title <span class="red">*</span></label>
                         <input type="text" name="name" placeholder="--Name" value="<?= htmlspecialchars($name) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="description">Description <span class="red">*</span></label>
                    <input type="text" name="description" placeholder="--Description" value="<?= htmlspecialchars($description) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="long_description">Long Description <span class="red">*</span></label>
                    <input type="text" name="long_description"  placeholder="--Long Description" value="<?= htmlspecialchars($long_description) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="publisher">Publisher</label>
                    <input type="text" name="publisher" placeholder="--Publisher" value="<?= htmlspecialchars($publisher) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="writer">Writer</label>
                    <input type="text" name="writer" placeholder="--Writer" value="<?= htmlspecialchars($writer) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="format">Format</label>
                    <input type="text" name="format" placeholder="--Format" value="<?= htmlspecialchars($format) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="age_rating">Age Rating</label>
                    <input type="text" name="age_rating" placeholder="--Age Rating" value="<?= htmlspecialchars($age_rating) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="price">Price <span class="red">*</span></label>
                    <input type="text" name="price" placeholder="--Price" value="<?= htmlspecialchars($price) ?>" />
                    </div>

                    <div class="form-group">
                    <label for="category_id">Category ID <span class="red">*</span></label>
                    <select name="category_id">
                         <option value=""> -- Select -- </option>
                         <?php foreach ($categories as $category): ?>
                              <option value="<?= htmlspecialchars($category['id']) ?>" <?= $category_id == $category['id'] ? 'selected' : '' ?>>
                                   <?= htmlspecialchars($category['name']) ?>
                              </option>
                         <?php endforeach; ?>
                    </select>
                    </div>

                    <div class="form-group">
                    <label for="category_id">Image <span class="red">*</span></label>
                    <div>
                         <label class="upload-button" for="productImage">
                              <img src="images/uploadImage.svg" class="width35" alt="Upload Image">
                         </label>
                         <input id="productImage" name="productImage" type="file" />
                    </div>
                    <span id="file-name"></span>
                    </div>

                    <?php
                    if ($msg == "") {
                    ?>
                         <div>
                              <button type="submit" class="vbutton text-none">Add New Product</button>
                         </div><br />
                    <?php
                    } else {
                         foreach ($errors as $error)
                              echo "<p class='error_red'>$error<p>";
                    }
                    ?>
               </form>

               <?php
               foreach ($errors as $error)
                    echo '<p class="error_red">' . $error . '</p><br/>';

               echo $msg;
               ?>
     </div>
     </section>
     </main>

 
     <?php include 'includes/footer.php'; ?>


