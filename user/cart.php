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
include '../includes/sidebar.php'; 
include '../auth/userAuth.php';
include '../includes/toast.php';
// include '../includes/inHeader.php'; 

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Fetch user address
$address_query = $conn->prepare("SELECT address FROM users WHERE userId = ?");
$address_query->bind_param("i", $_SESSION['userId']);
$address_query->execute();
$address_result = $address_query->get_result();
$user_address = ($address_result->num_rows > 0) ? $address_result->fetch_assoc()['address'] : "No address set.";

// Fetch cart items
$cart_items = $conn->prepare("
    SELECT cart.cartId, product.name, product.price, cart.quantity, (product.price * cart.quantity) AS total_price
    FROM cart
    JOIN product ON cart.productId = product.productId
    WHERE cart.userId = ?
");
$cart_items->bind_param("i", $_SESSION['userId']);
$cart_items->execute();
$result = $cart_items->get_result();
?>

<div class="container pt-5">
    <h2 class="mb-4 ">Shopping Cart</h2>

    <!-- Shipping Address Box -->
    <div class="card shadow-sm border-0 bg-light mb-4">
        <div class="card-body">
            <h4 class="card-title text-primary fs-4">Shipping Address</h4>
            <p class="fw-bold fs-10"><?= htmlspecialchars($user_address) ?></p>
            <a href="edit_address.php" class="btn btn-outline-primary btn-sm">Edit Address</a>
        </div>
    </div>

    <!-- Added to cart table  -->
    <?php if ($result->num_rows > 0) { ?>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td class="align-middle"><?= $row['name'] ?></td>
                        <td class="align-middle">₱<?= number_format($row['price'], 2) ?></td>
                        <td class="align-middle"><?= $row['quantity'] ?></td>
                        <td class="align-middle fw-bold text-success">₱<?= number_format($row['total_price'], 2) ?></td>
                        <td class="align-middle">
                            <a href="remove.php?cartId=<?= $row['cartId'] ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="checkout.php" class="btn btn-success w-100">Proceed to Checkout</a>
    <?php } else { ?>
        <div class="alert alert-warning text-center">Your cart is empty.</div>
    <?php } ?>
</div>


<?php include '../includes/footer.php'; ?>
    </body>
    </html>