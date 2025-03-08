<?php 
      include '../includes/inHeader.php';
      include '../includes/sidebar.php'; 
      include '../includes/config.php';
      include '../auth/adminAuth.php';

      $query = "SELECT COUNT(*) AS total_orders FROM orders";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $total_orders = $row['total_orders'];

      $query = "SELECT COUNT(*) AS total_cancel FROM orders WHERE orderStatus = 'Cancelled'" ;
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $total_cancel = $row['total_cancel'];

      $query = "SELECT COUNT(*) AS total_delivered FROM orders WHERE orderStatus = 'Delivered'";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $total_delivered = $row['total_delivered'];

      $query = "SELECT COUNT(*) AS total_pending FROM orders WHERE orderStatus = 'Pending'";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $total_pending = $row['total_pending'];

      $query = "SELECT COUNT(*) AS total_users FROM users";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $total_users = $row['total_users'];



?>

<div class="container-fluid mt-5 pt-5 text-center">
    <h2>Welcome, Admin!</h2>
    <p>Manage coffee shop inventory, orders, and users here.</p>

    <!-- 🔺 Top of Pyramid - Total Orders (Largest, Centered) -->
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4">
            <div class="mt-4 card text-white bg-primary mb-3 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title">Total Orders</h3>
                    <p class="card-text display-3 fw-bold"><?php echo $total_orders; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Middle of Pyramid - Status Cards (Medium, Side-by-Side) -->
    <div class="row justify-content-center">
        <!-- Cancelled Orders -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card text-white bg-danger mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Cancelled</h5>
                    <p class="card-text display-5"><?php echo $total_cancel; ?></p>
                </div>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card text-white bg-success mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Delivered</h5>
                    <p class="card-text display-5"><?php echo $total_delivered; ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card text-white bg-warning mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <p class="card-text display-5"><?php echo $total_pending; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔻 Bottom of Pyramid - Total Users (Smallest, Centered) -->
    <div class="row justify-content-center">
        <div class="col-sm-8 col-md-6 col-lg-4">
            <div class="card text-white bg-secondary mb-3 mt-4 shadow">
                <div class="card-body">
                    <h4 class="card-title">Total Users</h4>
                    <p class="card-text display-6 fw-bold"><?php echo $total_users; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php include '../includes/footer.php'; ?>
