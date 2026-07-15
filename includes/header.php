<?php
include_once __DIR__ . '/session_start.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
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
<header id="nav">
    <a href="index.php" class="brand-link">
        <img src="images/logo.png" id="logo" alt="Comic Book Shop">
        <span>The Comic Shop</span>
    </a>

    <nav id="navbar">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </div>

        <div class="nav-actions">
            <a href="cart.php" class="cart-link"><img src="images/icons/cart.png" class="width13" alt="" /> Cart</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="nav-user">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="?logout" class="nav-button">Logout</a>
            <?php else: ?>
                <a href="signup.php">Sign Up</a>
                <a href="login.php" class="nav-button">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
