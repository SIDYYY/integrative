<?php
include('../includes/config.php');

if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Login to access dashboard!";
    $_SESSION['code'] = "warning";
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['userRole'] !== 'User') {
    $_SESSION['message'] = "You are not authorized as USER!";
    $_SESSION['code'] = "warning";
    
    if ($_SESSION['userRole'] === 'Admin') {
        header("Location: ../Admin/index.php");
    } else {
        header("Location: ../login.php");
    }
    exit();
}
?>
