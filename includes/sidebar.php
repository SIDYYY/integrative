<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it's not already started
}

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- Sidebar -->
<div class="bg-dark text-white vh-100 p-3 fixed-left" style="width: 250px; position: fixed;">
    <h4 class="mb-4">CJ's Online Shop</h4>
    <ul class="nav flex-column">
        <?php if ($_SESSION['userRole'] == 'Admin'): ?> 
            <li class="nav-item"><a class="nav-link text-white" href="../admin/index.php"><i class="bi bi-house"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../admin/inventory.php"><i class="bi bi-box2-fill"></i> Inventory</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../admin/orders.php"><i class="bi bi-cart"></i> Orders</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../admin/users.php"><i class="bi bi-people"></i> Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../admin/users.php"><a href="../logout.php" class="btn btn-danger w-100">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link text-white" href="../user/index.php"><i class="bi bi-house"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../user/placeOrder.php"> Place Order </a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../user/manage.php"> Manage Orders</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../admin/users.php"><a href="../logout.php" class="btn btn-danger w-100">Logout</a></li>
        <?php endif; ?>
    </ul>
</div>
