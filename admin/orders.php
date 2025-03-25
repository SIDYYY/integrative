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
// include '../includes/inHeader.php';
include '../includes/config.php';
include '../includes/sidebar.php';
include '../includes/toast.php';
include '../auth/adminAuth.php';



// Fetch orders with necessary details
$query = "SELECT 
            o.orderId,
            u.firstName,
            u.lastName,
            p.name AS productName,
            o.quantity,
            p.price AS unitPrice,
            (o.quantity * p.price) AS totalPrice,
            o.orderStatus,
            o.orderDate
          FROM orders o
          JOIN product p ON o.productId = p.productId
          JOIN users u ON o.userId = u.userId
          ORDER BY o.orderDate DESC";

$result = mysqli_query($conn, $query);

// Handle Order Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['orderStatus'])) {
    $order_id = intval($_POST['order_id']);  // Sanitize input
    $order_status = $_POST['orderStatus'];

    // Allowed statuses (ensure only valid ENUM values are updated)
    $allowed_statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    if (in_array($order_status, $allowed_statuses)) {
        $update_query = "UPDATE orders SET orderStatus = ? WHERE orderId = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $order_status, $order_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Order - " [$order_status];
            $_SESSION['code'] = "success";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Error updating order status!');</script>";
        }

        $stmt->close();
    }
}
?>


<div class="container pt-5">
<h1>Orders</h1>

<!-- Table Datas  -->
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['orderId']; ?></td>
                        <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($row['productName']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>₱<?php echo number_format($row['unitPrice'], 2); ?></td>
                        <td>₱<?php echo number_format($row['totalPrice'], 2); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['orderId']; ?>">
                                <select name="orderStatus" class="form-select" onchange="this.form.submit()">
                                    <?php foreach (['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'] as $status): ?>
                                        <option value="<?php echo $status; ?>" <?php if ($row['orderStatus'] == $status) echo 'selected'; ?>>
                                            <?php echo $status; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td><?php echo date("F j, Y", strtotime($row['orderDate'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>  
            </body>
            </html>