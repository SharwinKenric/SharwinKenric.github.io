<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User is not logged in.");
}

$user_id = $_SESSION['user_id'];
if (isset($_GET['id']) && isset($_GET['delete'])) {
    $itemid = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $itemid);

    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully');</script>";
    } else {
        echo "<script>alert('Unsuccessful.');</script>";
    }

    $stmt->close();
}

$sql = "SELECT 
            cart.*, 
            items.itemname, 
            items.price, 
            users.firstname 
        FROM 
            cart 
        LEFT JOIN items ON cart.itemid = items.id 
        LEFT JOIN users ON cart.clientid = users.id 
        WHERE 
            users.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$subtotal = 0;
$fee = 100;
$item_ids = array();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
         footer {
            background-color: #f8f9fa;
            padding: 20px 0;
       
        }
        .footer-text {
            color: #6c757d;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><h3>Benta.ph</h3></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-3 active ">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item mr-3">
                        <a class="nav-link" href="About_Us.php">About Us</a>
                    </li>
                    <li class="nav-item mr-3">
                        <a class="nav-link " href="transaction_list.php" id="accountDropdown" role="button" 
                        aria-haspopup="true" aria-expanded="false"> Transaction
                        </a>
                    
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row pt-4">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th>Client Name</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                    $subtotal += ($row["price"] * $row["quantity"]);
                    $item_ids[] = $row['itemid']; 
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["firstname"]); ?></td>
                        <td><?php echo htmlspecialchars($row["itemname"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                        <td><?php echo number_format($row["price"], 2); ?></td>
                        <td><?php echo number_format($row["price"] * $row["quantity"], 2); ?></td>
                        <td>
                            <form method="GET" action="">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <div>
                <h4>Subtotal: <?php echo number_format($subtotal, 2); ?></h4>
                <h3>Fee: <?php echo number_format($fee, 2); ?></h3>
                <h2>Total Amount: <?php echo number_format($subtotal + $fee, 2); ?></h2>
                <form method="POST">
                    <input type="submit" class="btn btn-success" name="btncheckout" value="Checkout">
                </form>
                <?php
             if (isset($_POST["btncheckout"])) {

                if (!isset($_SESSION['user_id'])) {
                    die("User is not logged in.");
                }
                $user_id = $_SESSION['user_id'];
                $totalamount = $subtotal + $fee;
            
    
                $cart_items_sql = "SELECT itemid, quantity FROM cart WHERE clientid = ?";
                $stmt = $conn->prepare($cart_items_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $cart_items_result = $stmt->get_result();
            
                if ($cart_items_result->num_rows > 0) {
                    while ($cart_item = $cart_items_result->fetch_assoc()) {
                        $item_id = $cart_item['itemid'];
                        $quantity = $cart_item['quantity'];
            
                        $checkout_sql = "INSERT INTO transactions (user_id, item_id, totalAmount, quantity, status, orderDate) 
                                         VALUES (?, ?, ?, ?, 'Pending', NOW())";
                        $stmt = $conn->prepare($checkout_sql);
                        $stmt->bind_param("idii", $user_id, $item_id, $totalamount, $quantity);
                        if ($stmt->execute()) {
                            // Update item quantity
                            $update_item_sql = "UPDATE items SET quantity = quantity - ? WHERE id = ?";
                            $stmt_update = $conn->prepare($update_item_sql);
                            $stmt_update->bind_param("ii", $quantity, $item_id);
                            $stmt_update->execute();
                        } else {
                            echo "Error inserting item with ID $item_id into transactions table: " . $stmt->error;
                        }
                    }
            
                    $clear_cart_sql = "DELETE FROM cart WHERE clientid = ?";
                    $stmt_clear_cart = $conn->prepare($clear_cart_sql);
                    $stmt_clear_cart->bind_param("i", $user_id);
                    $stmt_clear_cart->execute();
            
                    echo "<script>alert('Thank you for Shopping!'); window.location='cart.php';</script>";
                } else {
                    echo "<script>alert('Your cart is empty'); window.location='cart.php';</script>";
                }
            
                $stmt->close();
                mysqli_close($conn);
            }?>
            </div>
        </div>
    </div>
    <footer class="footer mt-5 py-3 ">
        <div class="container text-center">
            <span class="footer-text">© 2024 Benta.ph. All rights reserved.</span>
        </div>
    </footer>
</body>
</html>