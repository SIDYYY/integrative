<?php
include '../includes/config.php'; 
include '../includes/sidebar.php'; 
include '../auth/userAuth.php';
include '../includes/toast.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];

// Fetch User Orders
$order_query = $conn->prepare("
    SELECT o.*, p.name AS product_name
    FROM orders o 
    JOIN product p ON o.productId = p.productId 
    WHERE o.userId = ? 
    ORDER BY o.orderDate DESC
");
$order_query->bind_param("i", $userId);
$order_query->execute();
$orders = $order_query->get_result();

// Handle Order Cancellation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_order'])) {
    $orderId = $_POST['order_id'];
    
    // Check if order is still pending
    $status_check = $conn->prepare("SELECT orderStatus FROM orders WHERE orderId = ? AND userId = ?");
    $status_check->bind_param("ii", $orderId, $userId);
    $status_check->execute();
    $result = $status_check->get_result();
    $order = $result->fetch_assoc();

    if ($order && $order['orderStatus'] == 'Pending') {
        // Update order status to "Cancelled"
        $cancel_order = $conn->prepare("UPDATE orders SET orderStatus = 'Cancelled' WHERE orderId = ?");
        $cancel_order->bind_param("i", $orderId);
        $cancel_order->execute();

        $_SESSION['message'] = "Item Cancelled Successfully!";
        $_SESSION['code'] = "success";
    } else {
        $_SESSION['message'] = "Error Changing Status Record: " . $conn->error;
        $_SESSION['code'] = "danger";
        }
    header("Location: manage.php");
    exit();
}   
?>

<!-- Table for user to track their order status  -->
<div class="container  pt-5">
    <h2>My Orders</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $orders->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_name']); ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td>₱<?= number_format($row['totalPrice'], 2); ?></td>
                    <td><?= $row['orderStatus']; ?></td>
                    <td><?= $row['orderDate']; ?></td>
                    <td>
                        <?php if ($row['orderStatus'] == 'Pending') : ?>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $row['orderId']; ?>">
                                <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        <?php else : ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
