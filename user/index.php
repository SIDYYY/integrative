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
      include '../auth/userAuth.php';
      include '../includes/sidebar.php'; 
      include '../includes/config.php'; 
      include '../includes/toast.php';?>

<div class="container pt-5 ">
    <h2>Welcome to Our Shop!</h2>
    <p>Explore our inventory and manage your orders effortlessly.</p>

    <!-- About the Shop Section -->
    <div class="card p-4 mt-4">
        <h3>About Our Shop</h3>
        <p>We are committed to providing high-quality products at unbeatable prices. Our shop offers a variety of items to meet your needs, from the latest trends to essential goods.</p>
    </div>

    <!-- Why Choose Us Section -->
    <div class="card p-4 mt-4">
        <h3>Why Shop With Us?</h3>
        <ul>
            <li>✔ High-quality products</li>
            <li>✔ Competitive pricing</li>
            <li>✔ Reliable customer service</li>
            <li>✔ Fast and secure transactions</li>
            <li>✔ Convenient online shopping experience</li>
        </ul>
    </div>

</div>



</div> 
<?php include '../includes/footer.php'?>
</body>
</html>

