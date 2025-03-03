<?php
include '../includes/config.php'; // Include database connection
include '../includes/sidebar.php'; 
include '../includes/inHeader.php'; 

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Get cart item count for the user
$cart_count_query = $conn->prepare("SELECT COUNT(DISTINCT productId) as total FROM cart WHERE userId = ?");
$cart_count_query->bind_param("i", $_SESSION['userId']);
$cart_count_query->execute();
$cart_count_result = $cart_count_query->get_result();
$cart_count = $cart_count_result->fetch_assoc()['total'];


// Handling "Add to Cart" Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Check if product exists
    $product_query = $conn->prepare("SELECT name, count, price FROM product WHERE productId = ?");
    $product_query->bind_param("i", $product_id);
    $product_query->execute();
    $result = $product_query->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['count'] >= $quantity) {
        // Check if the item is already in the cart
        $check_cart = $conn->prepare("SELECT * FROM cart WHERE userId = ? AND productId = ?");
        $check_cart->bind_param("ii", $_SESSION['userId'], $product_id);
        $check_cart->execute();
        $cart_result = $check_cart->get_result();

        if ($cart_result->num_rows > 0) {
            // If product exists in cart, update quantity
            $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE userId = ? AND productId = ?");
            $update_cart->bind_param("iii", $quantity, $_SESSION['userId'], $product_id);
            $update_cart->execute();
        } else {
            // If product is not in cart, insert new entry
            $add_to_cart = $conn->prepare("INSERT INTO cart (userId, productId, quantity) VALUES (?, ?, ?)");
            $add_to_cart->bind_param("iii", $_SESSION['userId'], $product_id, $quantity);
            $add_to_cart->execute();
        }

        echo "<script>alert('Product added to cart!'); window.location.href='placeOrder.php';</script>";
    } else {
        echo "<script>alert('Not enough stock available!'); window.location.href='placeOrder.php';</script>";
    }
}
?>

<div class="container mt-5 pt-5">
    <h2>Add to Cart</h2>

    <!-- Cart Button with Bootstrap Icon and Badge -->
    <a class="mt-4 btn btn-success position-relative mb-4" href="../user/cart.php">
        <i class="bi bi-cart"></i> Cart
        <?php if ($cart_count > 0) : ?> 
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $cart_count; ?>
            </span>
        <?php endif; ?>
    </a>

    <div class="row">
        <?php
        $products = $conn->query("SELECT * FROM product WHERE count > 0");
        while ($row = $products->fetch_assoc()) :
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                <div class="card-body">
                        <h5 class="card-title"><?= $row['name']; ?></h5>
                        <p class="card-text">₱<?= number_format($row['Price'], 2); ?></p>
                        <p class="text-muted">Stock: <?= $row['count']; ?></p>
                        
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['productId']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" class="form-control mb-2" min="1" max="<?= $row['count']; ?>" required>
                            <button type="submit" name="add_to_cart" class="btn btn-primary w-100">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>


<?php include '../includes/footer.php'; ?>
