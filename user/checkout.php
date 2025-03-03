<?php
include '../includes/config.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's address
$address_query = $conn->prepare("SELECT address FROM users WHERE userId = ?");
$address_query->bind_param("i", $_SESSION['userId']);
$address_query->execute();
$address_result = $address_query->get_result();
$user_address = ($address_result->num_rows > 0) ? $address_result->fetch_assoc()['address'] : "No address set.";

if ($user_address === "No address set.") {
    echo "<script>alert('Please update your shipping address first!'); window.location.href='cart.php';</script>";
    exit();
}

// Process order
$cart_query = $conn->prepare("SELECT * FROM cart WHERE userId = ?");
$cart_query->bind_param("i", $_SESSION['userId']);
$cart_query->execute();
$cart_result = $cart_query->get_result();

while ($cart = $cart_result->fetch_assoc()) {
    $product_id = $cart['productId'];
    $quantity = $cart['quantity'];

    // Get product details
    $product_query = $conn->prepare("SELECT name, count, price FROM product WHERE productId = ?");
    $product_query->bind_param("i", $product_id);
    $product_query->execute();
    $product_result = $product_query->get_result();
    $product = $product_result->fetch_assoc();

    if ($product && $product['count'] >= $quantity) {
        $total_price = $product['price'] * $quantity;
        $order_status = "Pending";
        $order_date = date("Y-m-d H:i:s");

        // Insert into orders
        $order_query = $conn->prepare("INSERT INTO orders (userId, productId, quantity, orderStatus, orderDate, shippingAddress, totalPrice) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $order_query->bind_param("iiisssd", $_SESSION['userId'], $product_id, $quantity, $order_status, $order_date, $user_address, $total_price);
        $order_query->execute();

        // Deduct stock
        $new_stock = $product['count'] - $quantity;
        $update_stock = $conn->prepare("UPDATE product SET count = ? WHERE productId = ?");
        $update_stock->bind_param("ii", $new_stock, $product_id);
        $update_stock->execute();
    }
}

// Clear cart after order
$conn->query("DELETE FROM cart WHERE userId = {$_SESSION['userId']}");

echo "<script>alert('Order placed successfully!'); window.location.href='index.php';</script>";
?>
