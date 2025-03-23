<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = null; // Default to null if not set
}

// Database Configuration
$host = "localhost";  
$dbname = "daguinotas&delapena";
$username = "root";  
$password = "";  

// Create MySQLi Connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to Check If User is Logged In
// function checkLogin() {
//     if (!isset($_SESSION['userId'])) {
//         header("Location: ../login.php"); 
//         exit();
//     }
// }

// // Function to Check Admin Role
// function checkAdmin() {
//     if ($_SESSION['role'] !== 'admin') {
//         header("Location: ../user/dashboard.php"); 
//         exit();
//     }
// }
// ?>
