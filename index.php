<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CJ's Online Shop</title>
    <link rel="icon" href="assets/img/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero-section {
            background: url('assets/img/shop.jpg') no-repeat center center/cover;
            height: 94.1vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
        .hero-content {
            text-align: center;
            max-width: 600px;
            padding: 50px;
            background: rgba( 255, 255, 255, 0.25 );
            box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
            backdrop-filter: blur( 11px );
            -webkit-backdrop-filter: blur( 11px );
            border-radius: 10px;
        }
        .hero-content h1, p {
            font-weight: bold;
        }
        .hero-content a{
            color: white;

        }
    </style>
</head>
<body>

    <!-- Header -->
    <?php 
    include './includes/header.php'; 
    ?>


    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content" >
            <h1>Welcome to CJ's Online Shop</h1>
            <p class="lead" style="color: black;">Enjoy the best offers online with our premium website and user friendly UI.</p>
            <a href="login.php" class="btn btn-dark btn-lg mt-3" >Get Started</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
