<?php
    $conn = mysqli_connect("localhost", "root", "", "db_shop");
    session_start();

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    // Check if client is already logged in
    if (!isset($_SESSION['user_id'])) {
        die("User is not logged in.");
    }
    if (isset($_GET['id'])) {
        $item_id = intval($_GET['id']);
        $price_query = mysqli_query($conn, "SELECT price FROM items WHERE id = $item_id");
        $price_row = mysqli_fetch_assoc($price_query);

        if ($price_row) {
            $price = $price_row['price'];
            $result = mysqli_query($conn, "SELECT quantity FROM cart WHERE clientid = $user_id AND itemid = $item_id");

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $new_quantity = $row['quantity'] + 1;
                $query = "UPDATE cart SET quantity = $new_quantity, price = $price WHERE clientid = $user_id AND itemid = $item_id";
            } else {
                $quantity = 1;
                $query = "INSERT INTO cart (clientid, itemid, quantity, price) VALUES ($user_id, $item_id, $quantity, $price)";
            }

            if (!mysqli_query($conn, $query)) {
           echo "<script>alert('Error adding/updating item in cart');</script>";
            }
        } else {
          echo "<script>alert('Item not found.');</script>";
        }
    }
    header("Location: dashboard.php");
    exit();

    mysqli_close($conn);
?>