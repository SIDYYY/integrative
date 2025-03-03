<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = null; // Default to null if not set
}

// Database Configuration
$host = "localhost";  
$dbname = "appdata";  
$username = "root";  
$password = "";  

// Create MySQLi Connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to Check If User is Logged In
function checkLogin() {
    if (!isset($_SESSION['userId'])) {
        header("Location: ../login.php"); 
        exit();
    }
}

// Function to Check Admin Role
function checkAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../user/dashboard.php"); 
        exit();
    }
}
?>
