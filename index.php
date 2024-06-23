<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benta.ph</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container-fluid" style="height: 100vh; margin-left: -15px;">
    <div class="row no-gutters" style="height: 100%;">
        <div class="col-md-6" style="background-image: url('landing.jpg'); background-size:; height: 100%; ">
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="height: 100%;">
            <div style="width: 75%;">
                <h5>Welcome to</h5><h1>Benta.ph</h1><br><br>
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required><br>
                    </div>
                    <h6 class="text-left mt-3">Go to the <a href="admin.php">Admin</a></h6>
                    <button type="submit" class="btn btn-primary btn-block">Login</button><br>
                    <?php
                            session_start();

                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $username = mysqli_real_escape_string($conn, $_POST['username']);
                                $password = mysqli_real_escape_string($conn, $_POST['password']);


                                $query = "SELECT id FROM users WHERE username='$username' AND password='$password'";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) == 1) {
                                    $row = mysqli_fetch_assoc($result);
                                    $_SESSION['user_id'] = $row['id'];
                                    header("Location: dashboard.php");
                                    exit;
                                } else {
                                    echo "<script>alert('Invalid username or password');</script>";
                                }
                            }
                            ?>

                </form>
                <form action="register.php">
                    <button type="submit" class="btn btn-outline-primary btn-block">Register</button>
                </form>
                <h6 class="text-left mt-3">Login <a href="loginfb.php">Facebook</a></h6>
               
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>