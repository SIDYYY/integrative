<?php
include '../includes/sidebar.php';
include '../includes/inHeader.php';
include '../includes/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_address = trim($_POST['address']);
    
    $update_query = $conn->prepare("UPDATE users SET address = ? WHERE userId = ?");
    $update_query->bind_param("si", $new_address, $_SESSION['userId']);
    
    if ($update_query->execute()) {
        echo "<script>alert('Address updated successfully!'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Error updating address.');</script>";
    }
}

// Fetch existing address
$address_query = $conn->prepare("SELECT address FROM users WHERE userId = ?");
$address_query->bind_param("i", $_SESSION['userId']);
$address_query->execute();
$address_result = $address_query->get_result();
$user_address = ($address_result->num_rows > 0) ? $address_result->fetch_assoc()['address'] : "";
?>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title text-center mb-3">Update Shipping Address</h4>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="address" class="form-label">Enter Shipping Address:</label>
                            <input type="text" name="address" id="address" class="form-control" value="<?= htmlspecialchars($user_address) ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php';?>