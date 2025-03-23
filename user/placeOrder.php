<?php
include '../includes/config.php'; 
include '../includes/sidebar.php'; 
include '../auth/userAuth.php';
include '../includes/toast.php';


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
    $product_query = $conn->prepare("SELECT name, quantity, price FROM product WHERE productId = ?");
    $product_query->bind_param("i", $product_id);
    $product_query->execute();
    $result = $product_query->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['quantity'] >= $quantity) {
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

        $_SESSION['message'] = "Added to Cart Successfully!";
        $_SESSION['code'] = "success";
    } else {
        $_SESSION['message'] = "Error : Cant Add to Cart: " . $conn->error;
        $_SESSION['code'] = "danger";
    }
    header("Location: placeOrder.php");
    exit();
}
// Search and category filter handling
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Query for products with search and filter
$query = "SELECT * FROM product WHERE quantity > 0";
$params = [];

if ($search) {
    $query .= " AND name LIKE ?";
    $params[] = "%" . $search . "%";
}

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
}

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $types = str_repeat("s", count($params)); 
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$products = $stmt->get_result();
?>

<div class="container mx-auto pt-5">
    <h2>Add to Cart</h2>

    <!-- Cart Button with Badge -->
    <a class="mt-4 btn btn-success position-relative mb-4" href="../user/cart.php">
        <i class="bi bi-cart"></i> Cart
        <?php if ($cart_count > 0) : ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $cart_count; ?>
            </span>
        <?php endif; ?>
    </a>

    <!-- form for fetching category   -->
    <form method="GET" action="placeOrder.php" class="mb-4 container-fluid">
        <div class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by product name"
                    value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php
                    $category_query = "SELECT DISTINCT category FROM product";
                    $category_result = mysqli_query($conn, $category_query);
                    while ($cat = mysqli_fetch_assoc($category_result)) {
                        $selected = ($category == $cat['category']) ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($cat['category']) . "' $selected>" . htmlspecialchars($cat['category']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form>

    <!-- Display a card of product information -->
    <div class="row">
        <?php while ($row = $products->fetch_assoc()) : ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="
                        <?php 
                            if ($row['picture']) { 
                                echo 'data:picture/jpeg;base64,' . base64_encode($row['picture']); 
                            } else { 
                                echo '../assets/no-image.png'; 
                            }
                        ?>
                    " class="card-img-top" alt="<?= htmlspecialchars($row['name']); ?>" style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text">₱<?= number_format($row['price'], 2); ?></p>
                        <p class="text-muted">Stock: <?= $row['quantity']; ?></p>

                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['productId']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" class="form-control mb-2" min="1" max="<?= $row['quantity']; ?>" required>
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