<?php

function redirect() {
    header("Location: index.php");
    exit();
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit(); 
    }
}

function redirectIfNotAdmin(PDO $pdo) {
    redirectIfNotLoggedIn();

    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(["id" => $_SESSION['user_id']]);
    $role = $stmt->fetchColumn();

    if ($role !== 'admin') {
        http_response_code(403);
        header('Location: index.php');
        exit();
    }

    $_SESSION['user_role'] = $role;
}

function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit(); 
    }
}
