<?php
include('../includes/config.php');

if(!isset($_SESSION['auth'])){
    $_SESSION['message'] = "Login to access dashboard!";
    $_SESSION['code'] = "warning";
    header("Location: ../login.php");
    exit();
}else
{
if($_SESSION['userRole'] != 'Admin')
{
    $_SESSION['message'] = "You are not authorized as ADMIN!";
    $_SESSION['code'] = "warning";
    header("Location: ../Admin/index.php");
    exit();
}
}


?>