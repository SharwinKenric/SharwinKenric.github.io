<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .registration-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="admindashboard.php"><h3>Benta.ph</h3></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-3 ">
                        <a class="nav-link" href="updateitem.php">Update</a>
                    </li>
                    <li class="nav-item mr-3 ">
                        <a class="nav-link" href="adminmanage.php">Manage</a>
                    </li>
                    <li class="nav-item mr-3 active">
                        <a class="nav-link" href="adminregister.php">Account</a>
                    </li>
                    <li class="nav-item mr-3 ">
                        <a class="nav-link" href="adminlogout.php">Logout</a>
                    </li>
                    </div>
                    </div>
                  
    </nav>
<div class="container">
    <div class="registration-form bg-white p-4 rounded">
        <h2 class="text-center">Register</h2>
        <form action="adminregister.php" method="POST">
        
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>

                        
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                
                $default_username = 'admin';
                $default_password = 'admin';

                if (!empty($password)) {
                    $check_username_query = "SELECT * FROM admin WHERE username='$default_username'";
                    $check_username_result = mysqli_query($conn, $check_username_query);

                    if (mysqli_num_rows($check_username_result) > 0) {
                        $update_query = "UPDATE admin SET password='$password' WHERE username='$default_username'";

                        if (mysqli_query($conn, $update_query)) {
                            echo "<script>alert('Password update successful.');</script>";
                        } else {
                            echo "<script>alert('Error');</script>";
                        }
                    } else {
                        $insert_query = "INSERT INTO admin (username, password) VALUES ('$default_username', '$default_password')";

                        if (mysqli_query($conn, $insert_query)) {
                            echo "<script>alert('Admin user created successfully with default credentials.');</script>";
                        } else {
                            echo "<script>alert('Please fill in all required fields.');</script>";
                        }
                    }
                }
            }
            ?>
        </form>
        <h6 class="text-center mt-3">Go back to the <a href="admin.php">Login</a> form</h6>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>