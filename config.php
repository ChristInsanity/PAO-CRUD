<?php
// Database configuration
$host = 'localhost';
$db = 'PAO';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}
function isUser() {
    return isLoggedIn() && $_SESSION['role'] === 'user';
}

function checkAuth() {
    if (!isLoggedIn()) {
        redirect('login.php'); 
    }
}

function checkAdminAuth() {
    if (!isAdmin()) {
        redirect('login.php');
    }
}
function checkUserAuth() {
    if (!isUser()) {
        redirect('login.php');
    }
}


// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
