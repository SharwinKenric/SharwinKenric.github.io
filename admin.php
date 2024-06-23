<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register Form</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6" style="margin-top:8%;">

            <h5>Welcome Admin to </h5>
           <h1>Benta.ph</h1><br>
            <form action="admin.php" method="POST" >
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="admin" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required><br>
                </div>
                <h6 class="text-left mt-3">Go to the <a href="index.php">Client</a></h6>
                <button type="submit" class="btn btn-primary btn-block">Login</button><br>

                
                <?php
                        session_start();


                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $default_username = 'admin';
                            $password = $_POST['password'];

                            $login_query = "SELECT * FROM admin WHERE username='$default_username' AND password='$password'";
                            $login_result = mysqli_query($conn, $login_query);

                            if (mysqli_num_rows($login_result) == 1) {
                                $_SESSION['admin_username'] = $default_username;
                                mysqli_close($conn);
                                header("Location: admindashboard.php");
                                exit();
                            } else {
                                $check_username_query = "SELECT * FROM admin WHERE username='$default_username'";
                                $check_username_result = mysqli_query($conn, $check_username_query);

                                if(mysqli_num_rows($check_username_result) == 0){
                                    echo "<script>alert('Admin account is not registered.');</script>";
                                } else {
                                    echo "<script>alert('Invalid username or password.');</script>";
                                }
                            }
                        }

                        if (isset($_SESSION['admin_username'])) {
                            header("Location: admindashboard.php");
                            exit();
                        }
                        ?>
                </form>
        </div>
            <div class="col-md-6"><br>
                <img src="benta.png" alt="no image" class="img-fluid">
            </div>
    </div>
</div>
</body>
</html>