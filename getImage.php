<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "db_shop");

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT profile FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile);
$stmt->fetch();
$stmt->close();
$conn->close();

// Set appropriate content type
header("Content-Type: image/jpeg");

// Output image data
echo $profile;
?>