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

// User fetch query 
$query = "SELECT `userId`,`firstName`,`lastName`,`email` FROM `users`";

$result = mysqli_query($conn, $query);
?>

        <div class="container pt-5">
<h1> Users </h1>

<!-- User Info Table -->
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['userId']; ?></td>
                <td><?php echo $row['firstName'] . ' ' . $row['lastName']; ?></td>
                <td><?php echo $row['email']; ?></td>

            </tr>
        <?php } ?>
    </tbody>
</table>
        </div>

<?php include '../includes/footer.php'; ?> 
        </body>
        </html>