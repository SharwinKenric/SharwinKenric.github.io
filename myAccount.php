<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "db_shop");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, firstname, lastname, username, contact, address, profile FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($id, $firstname, $lastname, $username, $contact, $address, $profile);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#"><h3>Benta.ph</h3></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-3">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item mr-3">
                    <a class="nav-link" href="About_Us.php">About Us</a>
                </li>
                <li class="nav-item mr-3 dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" 
                    aria-haspopup="true" aria-expanded="false"> Account
                    </a>
                    <div class="dropdown-menu" aria-labelledby="accountDropdown">
                        <a class="dropdown-item" href="myAccount.php">My Account</a>
                        <a class="dropdown-item" href="transaction_list.php">Transaction List</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <!-- Bootstrap navigation bar -->
    <!-- Add your navigation bar code here -->

    <div class="container mt-5">
        <h2>My Account</h2>
        <div class="">
            <div class="col-12 col-md-6 col-lg-5 mb-4">
                <ul>
                <li>Profile: <img src="getImage.php" class="card-img-top" alt="Profile Picture"></li>
                    <li>ID: <?php echo $id; ?></li>
                    <li>First Name: <?php echo $firstname; ?></li>
                    <li>Last Name: <?php echo $lastname; ?></li>
                    <li>Username: <?php echo $username; ?></li>
                    <li>Contact: <?php echo $contact; ?></li>
                    <li>Address: <?php echo $address; ?></li>
                </ul>
            </div>
        </div>
        <a href="account.php">Update account</a>
    </div>

    <!-- Bootstrap footer -->
    <!-- Add your footer code here -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>