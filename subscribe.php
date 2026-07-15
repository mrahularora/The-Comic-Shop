<?php
include 'includes/session_start.php';
include_once 'config/database.php';

$refererPath = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_PATH);
$redirect = $refererPath ? basename($refererPath) : 'index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $redirect");
    exit();
}

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    $_SESSION['newsletter_message'] = 'Please log in to subscribe with your account email.';
    header('Location: login.php');
    exit();
}

$email = trim($_SESSION['user_email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['newsletter_message'] = 'Your account email is not valid.';
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
