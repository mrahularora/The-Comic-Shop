<?php
include __DIR__ . '/session_start.php';
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/classes.php';

$db = new Database();
$product = new ProductItem($db->getConnection()); 
$cart = new ShoppingCart(); 

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php'); // Redirect to home page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comic Book Shop</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/skew.css">
    <link rel="stylesheet" href="assets/css/singleProduct.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div id="nav">
        <a href="index.php"><img src="images/logo.png" id="logo"></a>
    </div>
    <nav id="navbar">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop Comics</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="?logout" style="float:right;">Logout</a>
            <a href="cart.php" style="float:right;"><img src="images/icons/cart.png" class="width13" /> Cart</a>
            <a href="#" style="float:right;">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
            
        <?php else: ?>
            <a href="cart.php" style="float:right;"><img src="images/icons/cart.png" class="width13" /> Cart</a>
            <a href="login.php" style="float:right;">Admin Login</a>
            <a href="signup.php" style="float:right;">Sign Up</a>
            <a href="login.php" style="float:right;">Login</a>
            <a href="#" style="float:right;">Hi, Guest</a>
        <?php endif; ?>
        
    </nav>
    <div class="clearfix"></div>
</header>
