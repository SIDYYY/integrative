<?php
include('../includes/config.php');

if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Login to access dashboard!";
    $_SESSION['code'] = "warning";
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['userRole'] !== 'Admin') {
    $_SESSION['message'] = "You are not authorized as ADMIN!";
    $_SESSION['code'] = "warning";
    
    if ($_SESSION['userRole'] === 'user') {
        header("Location: ../user/index.php");
    } else {
        header("Location: ../login.php");
    }
    exit();
}
?>
