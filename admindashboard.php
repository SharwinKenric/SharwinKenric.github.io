<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_shop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['action']) && isset($_POST['transaction_id'])) {

    $transaction_id = $_POST['transaction_id'];
    $action = $_POST['action'];
    switch ($action) {
        case 'approve':
            // Update transaction status to Approved
            $update_query = "UPDATE transactions SET status='Approved' WHERE transaction_id=$transaction_id";
            if ($conn->query($update_query) === TRUE) {
                echo "<script>alert('Transaction approved successfully'); window.location='admindashboard.php';</script>";
            } else {
                echo "Error updating record: " . $conn->error;
            }
            break;
        case 'cancel':
            $items_query = "SELECT item_id, quantity FROM transactions WHERE transaction_id=$transaction_id";
            $items_result = $conn->query($items_query);
            if ($items_result && $items_result->num_rows > 0) {
                while ($item_row = $items_result->fetch_assoc()) {
                    $item_id = $item_row['item_id'];
                    $quantity = $item_row['quantity'];
                    $update_stock_query = "UPDATE items SET quantity = quantity + $quantity WHERE id = $item_id";
                    $conn->query($update_stock_query);
                }
                $update_query = "UPDATE transactions SET status='Cancelled' WHERE transaction_id=$transaction_id";
                if ($conn->query($update_query) === TRUE) {
                   echo "<script>alert('Transaction cancelled successfully'); window.location='admindashboard.php';</script>";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "<script>alert('No items found for this transaction'); window.location='admindashboard.php';</script>";
            }
            break;
        default:
            echo "Invalid action";
            break;
    }
}

$query = "SELECT t.transaction_id, t.totalAmount, t.orderDate, t.status, u.firstname, u.address, u.contact, ti.item_id
          FROM transactions t 
          JOIN users u ON t.user_id = u.id
          JOIN transactions ti ON t.transaction_id = ti.transaction_id
          ORDER BY t.orderDate DESC";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Transaction List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                <li class="nav-item mr-3 active">
                    <a class="nav-link" href="adminmanage.php">Manage</a>
                </li>
                <li class="nav-item mr-3 ">
                    <a class="nav-link" href="adminlogout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
   
    <h2>Admin - Transaction List</h2>
    <table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Item ID</th>
        <th>Total Amount</th>
        <th>Order Date</th>
        <th>Status</th>
        <th>Client Name</th>
        <th>Delivery Address</th>
        <th>Contact</th>
        
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['transaction_id']; ?></td>
                <td><?php echo $row['item_id']; ?></td>
                <td><?php echo $row['totalAmount']; ?></td>
                <td><?php echo $row['orderDate']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['firstname']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['contact']; ?></td>
              
                <td>
                        <form method="POST" style="display:inline-block">
                            <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                            <?php if ($row['status'] === 'Pending') { ?>
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm">Cancel</button>
                            <?php } else { ?>
                                <span class="text-muted">Action not available</span>
                            <?php } ?>
                        </form>
                    </td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='8'>No transactions found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
?>
</body>
</html>