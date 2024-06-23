<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'];
    $newContact = $_POST['new_contact'];
    $newAddress = $_POST['new_address'];
    $user_id = $_SESSION['user_id'];

    $update_query = "UPDATE users SET password=?, contact=?, address=? WHERE id=?";

    if ($stmt = mysqli_prepare($conn, $update_query)) {
        mysqli_stmt_bind_param($stmt, "sssi", $newPassword, $newContact, $newAddress, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Information updated successfully.');</script>";
        } else {
            echo "<script>alert('Error updating information: " . mysqli_error($conn) . "');</script>";
        }


        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement: " . mysqli_error($conn) . "');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#"><h3>Benta.ph</h3></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-3 ">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link" href="About_Us.php">About Us</a>
                </li>
                <li class="nav-item mr-3 dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" 
                    aria-haspopup="true" aria-expanded="false"> Account
                    </a>
                    <div class="dropdown-menu" aria-labelledby="accountDropdown">
                        <a class="dropdown-item" href="account.php">Update Account</a>
                        <a class="dropdown-item" href="transaction_list.php">Transaction List</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Account Settings</h2>
            <form action="account.php" method="POST">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="new_contact">New Contact</label>
                    <input type="text" class="form-control" id="new_contact" name="new_contact">
                </div>
                <div class="form-group">
                    <label for="new_address">New Address</label>
                    <textarea class="form-control" id="new_address" name="new_address"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button><br>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>