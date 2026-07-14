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

function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit(); 
    }
}