<?php 
// include '../includes/inHeader.php';
include '../includes/config.php';
include '../includes/sidebar.php';
include '../includes/toast.php';
include '../auth/adminAuth.php';


// update product 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = $_POST['edit_product_id'];
    $name = $_POST['edit_name'];
    $category = $_POST['edit_category'];
    $price = $_POST['edit_price'];
    $stock = $_POST['edit_stock'];

    $update_query = "UPDATE product SET name = ?, category = ?, price = ?, quantity = ? WHERE productId = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
        $_SESSION['code'] = "success";
    } else {
        $_SESSION['message'] = "Error updating product: " . $conn->error;
        $_SESSION['code'] = "danger";
    }
    $stmt->close();
    header("Location: inventory.php");
    exit();
}
// delete product 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
    $delete_query = "DELETE FROM product WHERE productId = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record deleted successfully!";
        $_SESSION['code'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting record: " . $conn->error;
        $_SESSION['code'] = "danger";
    }
    $stmt->close();
    header("Location: inventory.php");
    exit();
}

// add product 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addProduct"])) {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // Handle file upload
    $imageData = null;
    if (!empty($_FILES["picture"]["tmp_name"])) {
        $imageData = file_get_contents($_FILES["picture"]["tmp_name"]);
    }

    // Insert data into the database
    $query = "INSERT INTO product (name, category, price, quantity, picture) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdis", $name, $category, $price, $quantity, $imageData);

    if ($stmt->execute()) {
        $_SESSION["message"] = "Product added successfully!";
        $_SESSION["code"] = "success";
    } else {
        $_SESSION["message"] = "Error: " . $stmt->error;
        $_SESSION["code"] = "danger";
    }

    $stmt->close();
    header("Location: inventory.php");
    exit();
}

// Fetch products
$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);
?>

<!-- Add Product & Header  -->
<div class="container  pt-5">
    <h1>Inventory </h1>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button class="btn btn-success mb-4" type="button" data-toggle="modal" data-target="#addProductModal">Add Product</button>
</div>

<!-- Search filter & Category Section  -->
<form method="GET" action="inventory.php" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by product name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php
                    $category_query = "SELECT DISTINCT category FROM product";
                    $category_result = mysqli_query($conn, $category_query);
                    while ($cat = mysqli_fetch_assoc($category_result)) {
                        $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? "selected" : "";
                        echo "<option value='" . htmlspecialchars($cat['category']) . "' $selected>" . htmlspecialchars($cat['category']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php
        // Default query
        $query = "SELECT * FROM product WHERE 1=1";

        // Search condition
        if (!empty($_GET['search'])) {
            $search = "%" . $_GET['search'] . "%";
            $query .= " AND name LIKE ?";
        }

        // Category condition
        if (!empty($_GET['category'])) {
            $query .= " AND category = ?";
        }

        // Prepare statement
        $stmt = $conn->prepare($query);
        if (!empty($_GET['search']) && !empty($_GET['category'])) {
            $stmt->bind_param("ss", $search, $_GET['category']);
        } elseif (!empty($_GET['search'])) {
            $stmt->bind_param("s", $search);
        } elseif (!empty($_GET['category'])) {
            $stmt->bind_param("s", $_GET['category']);
        }

        // Execute and fetch results
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?= !empty($row['picture']) ? 'data:image/jpeg;base64,' . base64_encode($row['picture']) : 'default-image.jpg'; ?>" class="card-img-top" alt="Product Image" style="width:100%; height:200px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text">Category: <?= htmlspecialchars($row['category']); ?></p>
                        <p class="card-text">Price: ₱<?= number_format($row['price'], 2); ?></p>
                        <p class="card-text">Stock: <?= htmlspecialchars($row['quantity']); ?></p>
                        <button class="btn btn-warning edit-btn" 
                            data-id="<?= $row['productId']; ?>" 
                            data-name="<?= htmlspecialchars($row['name']); ?>" 
                            data-category="<?= htmlspecialchars($row['category']); ?>" 
                            data-price="<?= $row['price']; ?>" 
                            data-stock="<?= $row['quantity']; ?>" 
                            data-bs-toggle="modal" data-bs-target="#editProductModal">
                            Edit
                        </button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $row['productId']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php }
        $stmt->close();
        ?>
    </div>
</div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product/Recipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST"  enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Category</label>
                        <input type="text" class="form-control" id="productCategory" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="productPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="productStock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="productStock" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="picture">
                    </div>
                    <button type="submit" class="btn btn-primary" name="addProduct">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="inventory.php">
                    <input type="hidden" name="edit_product_id" id="edit_product_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="edit_category" name="edit_category" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_price" name="edit_price" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="edit_stock" name="edit_stock" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script for Update/Edit Modal   -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit_product_id").value = this.dataset.id;
            document.getElementById("edit_name").value = this.dataset.name;
            document.getElementById("edit_category").value = this.dataset.category;
            document.getElementById("edit_price").value = this.dataset.price;
            document.getElementById("edit_stock").value = this.dataset.stock;
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
