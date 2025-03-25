<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white vh-100 p-3 fixed-left" style="width: 250px;">
        <h4 class="mb-4">Dashboard</h4>
        <ul class="nav flex-column">
            <?php if ($_SESSION['role'] == 'Admin'): ?>
                <li class="nav-item"><a class="nav-link text-white" href="../../admin/index.php"><i class="bi bi-house"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../../admin/inventory.php"><i class="bi bi-box2-fill"></i> Inventory</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../../admin/orders.php"><i class="bi bi-cart"></i> Orders</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../../admin/users.php"><i class="bi bi-people"></i> Users</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link text-white" href="../../user/index.php"><i class="bi bi-house"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../../user/placeOrder.php"> Place Order </a></li>
                <li class="nav-item"><a class="nav-link text-white" href="../../user/manage.php"> Manage Orders</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="p-4" style="flex: 1;">