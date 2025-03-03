<?php include '../includes/inHeader.php'; ?>
<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/config.php'; // Database connection ?>

<div class="container mt-5 pt-5 ">
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

    

    <!-- Product Display Section -->
    <div class="mt-4">
        <h3>Our Products</h3>
        <div class="row">
            <?php
            $query = "SELECT * FROM product";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col-md-4 mt-3">';
                echo '<div class="card">';
                // echo '<img src="' . $row['image_url'] . '" class="card-img-top" alt="' . $row['name'] . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['name'] . '</h5>';
                // echo '<p class="card-text">' . $row['description'] . '</p>';
                echo '<p class="card-text"><strong>Price: </strong>₱ ' . $row['Price'] . '</p>';
                echo '<a href="product_details.php?productId=' . $row['productId'] . '" class="btn btn-primary">View Details</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    

</div>



</div> <!-- Close main content div from sidebar.php -->
</body>
</html>

<?php include '../includes/footer.php'?>