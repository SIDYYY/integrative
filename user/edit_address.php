<?php
include '../includes/sidebar.php';
include '../auth/userAuth.php';
include '../includes/config.php';
include '../includes/toast.php';


// Update Users Address 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_address = trim($_POST['address']);
    
    $update_query = $conn->prepare("UPDATE users SET address = ? WHERE userId = ?");
    $update_query->bind_param("si", $new_address, $_SESSION['userId']);
    
    if ($update_query->execute()) {
        $_SESSION['message'] = "Address Updated Successfully!";
        $_SESSION['code'] = "success";
    } else {
        $_SESSION['message'] = "Error : Failed to  Update Your Address!";
        $_SESSION['code'] = "danger";
    }
    header("Location: cart.php");
    exit();
}

// Fetch existing address
$address_query = $conn->prepare("SELECT address FROM users WHERE userId = ?");
$address_query->bind_param("i", $_SESSION['userId']);
$address_query->execute();
$address_result = $address_query->get_result();
$user_address = ($address_result->num_rows > 0) ? $address_result->fetch_assoc()['address'] : "";
?>

<!-- Address Information Form  -->
<div class="container pt-5">
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

