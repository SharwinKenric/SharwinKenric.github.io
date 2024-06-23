<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");
session_start();

// Check if the profiles directory exists, if not, create it
$profilesDirectory = __DIR__ . "/profiles";
if (!is_dir($profilesDirectory)) {
    mkdir($profilesDirectory);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $profile = null;

    // Handle file upload
    if (!empty($_FILES['profile']['tmp_name'])) {
        $profileFileName = $_FILES['profile']['name'];
        $profileFilePath = $profilesDirectory . '/' . $profileFileName;
        if (move_uploaded_file($_FILES['profile']['tmp_name'], $profileFilePath)) {
            $profile = $profileFilePath;
        } else {
            echo "<script>alert('Failed to move uploaded file.');</script>";
        }
    }

    if (!empty($firstname) && !empty($lastname) && !empty($username) && !empty($password) && !empty($contact) && !empty($address)) {
        $check_username_query = "SELECT * FROM users WHERE username='$username'";
        $check_username_result = mysqli_query($conn, $check_username_query);

        if (mysqli_num_rows($check_username_result) > 0) {
            echo "<script>alert('Username already exists. Please choose another username.');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password, contact, address, profile) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $firstname, $lastname, $username, $password, $contact, $address, $profile);

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
        <h2 class="text-center">Register</h2>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Details</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="address">Delivery Address</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="profile">Profile</label>
                <input type="file" class="form-control" id="profile" name="profile">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <h6 class="text-center mt-3">Go back to the <a href="index.php">Login</a> form</h6>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>