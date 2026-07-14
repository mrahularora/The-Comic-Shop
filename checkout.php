<?php
include 'includes/session_start.php';
include_once 'includes/classes.php';
include_once 'config/database.php';
include_once 'includes/functions.php';
redirectIfNotLoggedIn();

$db = new Database();
$cart = new ShoppingCart(); 
$product = new ProductItem($db->getConnection());
$order = new Order($db->getConnection()); 

$errors = [];

$name = $contact = $address = $zip_code = $card_number = $card_expiry = $card_cvv = $cardholder_name = '';
$cart_items = $cart->getCart();
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $zip_code = isset($_POST['zip_code']) ? trim($_POST['zip_code']) : '';

    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $card_expiry = isset($_POST['card_expiry']) ? trim($_POST['card_expiry']) : '';
    $card_cvv = isset($_POST['card_cvv']) ? trim($_POST['card_cvv']) : '';
    $cardholder_name = isset($_POST['cardholder_name']) ? trim($_POST['cardholder_name']) : '';

    // Validation checks
    if (empty($name)) {
        $errors['name'] = '<span class="red">Full Name is Required.</span>';
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors['name'] = '<span class="red">Full Name should only contain letters and spaces.</span>';
    }

    if (empty($contact)) {
        $errors['contact'] = '<span class="red">Contact Number is Required.</span>';
    } elseif (!preg_match("/^\d{10}$/", $contact)) {
        $errors['contact'] = '<span class="red">Contact Number should be 10 digits.</span>';
    }

    if (empty($address)) {
        $errors['address'] = '<span class="red">Address is Required.</span>';
    }

    if (empty($zip_code)) {
        $errors['zip_code'] = '<span class="red">Zip Code is Required.</span>';
    } elseif (!preg_match("/^[a-zA-Z0-9]{6}$/", $zip_code)) {
        $errors['zip_code'] = '<span class="red">Zip Code should be 6 alphanumeric characters.</span>';
    }

    if (empty($card_number)) {
        $errors['card_number'] = '<span class="red">Card Number is Required.</span>';
    } elseif (!preg_match("/^\d{16}$/", $card_number)) {
        $errors['card_number'] = '<span class="red">Card Number should be 16 digits.</span>';
    }

    if (empty($card_expiry)) {
        $errors['card_expiry'] = '<span class="red">Expiration Date is Required.</span>';
    } elseif (!preg_match("/^(0[1-9]|1[0-2])\/?([0-9]{2})$/", $card_expiry)) {
        $errors['card_expiry'] = '<span class="red">Expiration Date should be in MM/YY format.</span>';
    }

    if (empty($card_cvv)) {
        $errors['card_cvv'] = '<span class="red">CVV is Required.</span>';
    } elseif (!preg_match("/^\d{3}$/", $card_cvv)) {
        $errors['card_cvv'] = '<span class="red">CVV should be 3 digits.</span>';
    }

    if (empty($cardholder_name)) {
        $errors['cardholder_name'] = '<span class="red">Cardholder Name is Required.</span>';
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $cardholder_name)) {
        $errors['cardholder_name'] = '<span class="red">Cardholder Name should only contain letters and spaces.</span>';
    }

    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];

        $order_id = $order->placeOrder($user_id, $cart_items, $contact, $address, $zip_code);

        $cart->emptyCart();

        header("Location: pdfCheckout.php?order_id=$order_id");
        exit();
    }
}
?>
<?php include 'includes/header.php'; ?>

<!-- Main Content -->

<main class="mid75">
    <section class="marginbottom30 margin70">

    <h1 class="center">Checkout</h1><br />

    <div class="width60 marginauto">
    <a href="cart.php" class="button text-none">Back to Cart</a><br /><br />

    <?php echo @$display; ?><br />

    <form method="post" onsubmit="return validateForm()">

            <h1>Customer Details</h1><Br />
            <div class="form-group">
                <label for="name">Full Name<span class="red">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" >
                <small id="name-error" ><?php echo $errors['name'] ?? ''; ?></small>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number<span class="red">*</span></label>
                <input type="text" id="contact" name="contact" class="form-control" value="<?php echo htmlspecialchars($contact); ?>" >
                <small id="contact-error" ><?php echo $errors['contact'] ?? ''; ?></small>
            </div>
            <div class="form-group">
                <label for="address">Address<span class="red">*</span></label>
                <textarea id="address" name="address" cols="50" rows="6" class="form-control" ><?php echo htmlspecialchars($address); ?></textarea>
                <small id="address-error" ><?php echo $errors['address'] ?? ''; ?></small>
            </div>
            <div class="form-group">
                <label for="zip_code">Zip Code<span class="red">*</span></label>
                <input type="text" id="zip_code" name="zip_code" class="form-control" value="<?php echo htmlspecialchars($zip_code); ?>" >
                <small id="zip_code-error" ><?php echo $errors['zip_code'] ?? ''; ?></small>
            </div>

        
            <h1>Payment Details</h1>
            <div class="form-group">
                <label for="card_number">Card Number<span class="red">*</span></label>
                <input type="text" id="card_number" name="card_number" class="form-control" value="<?php echo htmlspecialchars($card_number); ?>" >
                <small id="card_number-error" ><?php echo $errors['card_number'] ?? ''; ?></small>
            </div>
            <div class="form-group">
                <label for="card_expiry">Expiration Date<span class="red">*</span></label>
                <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY" value="<?php echo htmlspecialchars($card_expiry); ?>" >
                <small id="card_expiry-error" ><?php echo $errors['card_expiry'] ?? ''; ?></small>
            </div>
            <div class="form-group">
                <label for="card_cvv">CVV<span class="red">*</span></label>
                <input type="text" id="card_cvv" name="card_cvv" class="form-control" value="<?php echo htmlspecialchars($card_cvv); ?>" >
                <small id="card_cvv-error" ><?php echo $errors['card_cvv'] ?? ''; ?></small>
            </div>
            <div class="form-group">
                <label for="cardholder_name">Cardholder Name<span class="red">*</span></label>
                <input type="text" id="cardholder_name" name="cardholder_name" class="form-control" value="<?php echo htmlspecialchars($cardholder_name); ?>" >
                <small id="cardholder_name-error" ><?php echo $errors['cardholder_name'] ?? ''; ?></small>
            </div>

        <button type="submit" class="vbutton text-none">Place Order</button>
        <a href="cart.php" class="button text-none">Cancel</a>
    </form>
    
    </div>
    

</section>
</main>

<?php include 'includes/footer.php'; ?>
