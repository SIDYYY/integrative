<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100"
style="background: url('https://png.pngtree.com/background/20230412/original/pngtree-coffee-background-cartoon-border-illustration-picture-image_2396465.jpg') no-repeat center center/cover;">>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <h3 class="text-center mb-4">Login</h3>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        include 'includes/config.php';

                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
                        $result = mysqli_query($conn, $query);
                        $user = mysqli_fetch_assoc($result);

                        if (password_verify($password, $user['password'])) {  
                            $_SESSION['userId'] = $user['userId'];
                            $_SESSION['role'] = $user['role']; 

                            if ($user['role'] == 'Admin') {
                                header("Location: admin/index.php");
                            } else {
                                header("Location: user/index.php");
                            }
                            exit();
                        } else {
                            echo '<div class="alert alert-danger text-center">Invalid login credentials!</div>';
                        }
                    }
                    ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <p class="text-center mt-3">
                        <a href="register.php">Don't have account yet? </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
