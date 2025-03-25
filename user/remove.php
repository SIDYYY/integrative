<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CJ's Online Shop</title>
    <!-- Add your CSS links here -->
    <link rel="stylesheet" href="path/to/your/styles.css">
</head>
<body>
<?php
include '../includes/config.php'; 
include '../includes/toast.php';
include '../auth/userAuth.php';

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['cartId'])) {
    $cart_id = $_GET['cartId'];

    // Prepare delete statement
    $delete_query = $conn->prepare("DELETE FROM cart WHERE cartId = ? AND userId = ?");
    $delete_query->bind_param("ii", $cart_id, $_SESSION['userId']);
    $delete_query->execute();

    if ($delete_query->affected_rows > 0) {
        $_SESSION['message'] = "Item Removed from Cart!";
        $_SESSION['code'] = "success";
    } else {
        $_SESSION['message'] = "Error : Failed to remove from Cart!";
        $_SESSION['code'] = "danger";
}
header("Location: cart.php");
exit();
} else {
    header("Location: cart.php");
    exit();
}
?>

<!-- Basically Removing Product/Item from the CART  -->

</body>
</html>