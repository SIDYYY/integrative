<?php 
include '../includes/inHeader.php';
include '../includes/config.php';
include '../includes/sidebar.php';

// Handle delete request before rendering table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['delete_id'];

    $delete_query = "DELETE FROM product WHERE productId = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

// Handle stock update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_stock'])) {
    $id = $_POST['product_id'];
    $new_stock = $_POST['new_stock'];

    $update_query = "UPDATE product SET count = ? WHERE productId = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $new_stock, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Stock updated successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "Error updating stock: " . $conn->error;
    }
    $stmt->close();
}

// Handle new product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $insert_query = "INSERT INTO product (name, Category, Price, count) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssdi", $name, $category, $price, $stock);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href='inventory.php';</script>";
    } else {
        echo "Error adding product: " . $conn->error;
    }
    $stmt->close();
}
?>

<div class="container mt-5 pt-5">
    <h1>Inventory</h1>

    <!-- Button to Open the Add Product Modal -->
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">

    <button class="btn btn-success mb-4" type="button" data-toggle="modal" data-target="#addProductModal">Add Product</button>
    
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group ">
                            <label class="mt-2">Product Name</label>
                            <input type="text" name="name" class="form-control mt-1" required>
                        </div>
                        <div class="form-group">
                            <label class="mt-2">Category</label>
                            <input type="text" name="category" class="form-control mt-1" required>
                        </div>
                        <div class="form-group">
                            <label class="mt-2">Price</label>
                            <input type="number" name="price" step="0.01" class="form-control mt-1" required>
                        </div>
                        <div class="form-group">
                            <label class="mt-2">Stock</label>
                            <input type="number" name="stock" class="form-control mt-1" required>
                        </div>
                        <button type="submit" name="add_product" class="mt-4 btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM product";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['productId']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Category']); ?></td>
                        <td>₱<?php echo number_format($row['Price'], 2); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $row['productId']; ?>">
                                <input type="number" name="new_stock" value="<?= $row['count']; ?>" min="0" required>
                                <button type="submit" name="update_stock" class="btn btn-primary">Update</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $row['productId']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">No products available</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
