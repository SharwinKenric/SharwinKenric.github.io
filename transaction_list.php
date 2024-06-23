<?php
session_start();

// Establish database connection
$conn = new mysqli("localhost", "root", "", "db_shop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$current_user_id = $_SESSION['user_id'];

if (isset($_POST['action']) && isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
    $action = $_POST['action'];
    $status_query = "SELECT status FROM transactions WHERE transaction_id=? AND user_id=?";
    $stmt_status = $conn->prepare($status_query);
    if ($stmt_status) {
        $stmt_status->bind_param("ii", $transaction_id, $current_user_id);
        $stmt_status->execute();
        $stmt_status->bind_result($current_status);
        $stmt_status->fetch();
        $stmt_status->close();
    } else {
        echo "Error preparing status query: " . $conn->error;
        exit();
    }

    if ($current_status == 'Cancelled') {
        echo "Transaction is already cancelled.";
        exit();
    }

    switch ($action) {
        case 'approve':
            $new_status = 'Completed';
            break;
        case 'cancel':
            $new_status = 'Cancelled';

            $update_query = "UPDATE transactions SET status=? WHERE transaction_id=? AND user_id=?";
            $stmt_update = $conn->prepare($update_query);
            if ($stmt_update) {
                $stmt_update->bind_param("sii", $new_status, $transaction_id, $current_user_id);
                $stmt_update->execute();
                $stmt_update->close();

                $item_query = "SELECT item_id, quantity FROM transactions WHERE transaction_id=?";
                $stmt_item = $conn->prepare($item_query);
                if ($stmt_item) {
                    $stmt_item->bind_param("i", $transaction_id);
                    $stmt_item->execute();
                    $result_item = $stmt_item->get_result();

                    while ($row_item = $result_item->fetch_assoc()) {
                        $item_id = $row_item['item_id'];
                        $quantity = $row_item['quantity'];

                        $update_item_query = "UPDATE items SET quantity = quantity + ? WHERE id = ?";
                        $stmt_update_item = $conn->prepare($update_item_query);
                        if ($stmt_update_item) {
                            $stmt_update_item->bind_param("ii", $quantity, $item_id);
                            $stmt_update_item->execute();
                            $stmt_update_item->close();
                        } else {
                            echo "Error preparing item update query: " . $conn->error;
                        }
                    }
                    $stmt_item->close();
                } else {
                    echo "Error preparing item query: " . $conn->error;
                }
            } else {
                echo "Error preparing transaction update query: " . $conn->error;
            }
            break;
        case 'complete':
            $new_status = 'Completed';
            break;
        default:
            $new_status = '';
            break;
    }

    if ($new_status && $action !== 'cancel') {
        $update_query = "UPDATE transactions SET status=? WHERE transaction_id=? AND user_id=?";
        $stmt_update = $conn->prepare($update_query);
        if ($stmt_update) {
            $stmt_update->bind_param("sii", $new_status, $transaction_id, $current_user_id);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            echo "Error preparing update query: " . $conn->error;
        }
    }
}

// Fetch user transactions
$query = "SELECT t.transaction_id, t.totalAmount, t.orderDate, t.status, u.firstname, u.address, u.contact, t.item_id, t.quantity
          FROM transactions t 
          JOIN users u ON t.user_id = u.id
          WHERE t.user_id = ?
          ORDER BY t.orderDate DESC";

$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    echo "Error preparing select query: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                <li class="nav-item mr-3">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item mr-3 ">
                    <a class="nav-link" href="About_Us.php">About Us</a>
                </li>
                <li class="nav-item mr-3 dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Account</a>
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

<div class="container mt-3">
    <div class="row justify-content-center">
    <h2>Transactions</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client Name</th>
                <th>Contact</th>
                <th>Delivery Address</th>
                <th>Order Date</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['transaction_id']; ?></td>
                    <td><?php echo $row['firstname']; ?></td>
                    <td><?php echo $row['contact']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['orderDate']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['totalAmount']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                            <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="footer-text">© 2024 Benta.ph. All rights reserved.</span>
        </div>
    </footer>

<!-- Bootstrap and other scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>