<?php
include '../includes/config.php'; // Include database connection
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
        echo "<script>alert('Item removed from cart!'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Error removing item.'); window.location.href='cart.php';</script>";
    }
} else {
    header("Location: cart.php");
    exit();
}
?>
