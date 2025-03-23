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


<!-- Login Form & Function -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <h3 class="text-center mb-4">Login</h3>

                    <?php
                    session_start();
                    include 'includes/config.php';

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $email = trim($_POST['email']);
                        $password = trim($_POST['password']);

                        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();

                        if ($user && password_verify($password, $user['password'])) {
                            $_SESSION['auth'] = true;
                            $_SESSION['userId'] = $user['userId'];
                            $_SESSION['userRole'] = $user['role']; 
                            $_SESSION['authUser'] = [
                                'userId' => $user['userId'],
                                'fullName' => $user['firstName'] . ' ' . $user['lastName'],
                                'emailAddress' => $user['email'],
                            ];

                            $_SESSION['message'] = "Welcome " . $_SESSION['authUser']['fullName'] . "!";
                            $_SESSION['code'] = "info";

                            if ($user['role'] === 'Admin') {
                                header("Location: admin/index.php");
                            } else {
                                header("Location: user/index.php");
                            }
                            exit();
                        } else {
                            $_SESSION['message'] = "Invalid login credentials!";
                            $_SESSION['code'] = "danger";
                            header("Location: login.php");
                            exit();
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php
  if(isset($_SESSION['message']) && $_SESSION['code'] !='') {
      ?>
      <script>
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: "<?php echo $_SESSION['code']; ?>",
          title: "<?php echo $_SESSION['message']; ?>"
        });
      </script>
      <?php
      unset($_SESSION['message']);
      unset($_SESSION['code']);
  }     
?>
</body>
</html>
