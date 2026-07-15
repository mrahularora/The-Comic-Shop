<?php
include 'includes/session_start.php';
include_once 'config/database.php';

$refererPath = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_PATH);
$redirect = $refererPath ? basename($refererPath) : 'index.php';
$email = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['newsletter_message'] = 'Please enter a valid email address.';
    header("Location: $redirect");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$conn->exec("
    CREATE TABLE IF NOT EXISTS newsletter_subscribers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$stmt = $conn->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (:email)");
$stmt->execute(['email' => $email]);

$_SESSION['newsletter_message'] = $stmt->rowCount() > 0
    ? 'Thanks for subscribing to The Comic Shop.'
    : 'You are already subscribed.';

header("Location: $redirect");
exit();
