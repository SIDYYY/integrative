<?php
include(__DIR__ . '../includes/config.php');

if(!isset($_SESSION['auth'])){
    $_SESSION['message'] = "Login to access dashboard!";
    $_SESSION['code'] = "warning";
    header("Location: ../login.php");
    exit();
}else
{
if($_SESSION['userRole'] != 'admin')
{
    $_SESSION['message'] = "You are not authorized as USER!";
    $_SESSION['code'] = "warning";
    header("Location: ../user/index.php");
    exit();
}
}


?>