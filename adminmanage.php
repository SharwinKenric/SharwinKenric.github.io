<?php
$conn = new mysqli('localhost', 'root', '', 'db_shop');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add_category') {
        $category = $conn->real_escape_string($_POST['category']);
        $sql = "INSERT INTO categories (name) VALUES ('$category')";
        if ($conn->query($sql) === TRUE) {
           echo "<script>alert('New category created successfully');</script>";
        } else {
            echo "<script>alert('error');</script>";
        }
    }

    if ($action === 'add_item') {
        $itemname = $conn->real_escape_string($_POST['itemname']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = $conn->real_escape_string($_POST['price']);
        $quantity = $conn->real_escape_string($_POST['quantity']);
        $category_id = $conn->real_escape_string($_POST['category_id']);

        $img = '';
        if (!empty($_FILES['img']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                $img = basename($_FILES["img"]["name"]);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $sql = "INSERT INTO items (itemname, description, price, quantity, img, category) 
                VALUES ('$itemname', '$description', '$price', '$quantity', '$img', '$category_id')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Item created.');</script>";
        } else {
            echo "<script>alert('Error');</script>";
        }
    }

    if ($action === 'delete_category') {
        $category_id = $conn->real_escape_string($_POST['category_id']);
        $sql = "DELETE FROM categories WHERE id='$category_id'";
        if ($conn->query($sql) === TRUE) {
          echo "<script>alert('Category deleted successfully');</script>";
        } else {
            echo "<script>alert('Error');</script>";
        }
    }

    if ($action === 'delete_item') {
        $item_id = $conn->real_escape_string($_POST['item_id']);
        $sql = "DELETE FROM items WHERE id='$item_id'";
        if ($conn->query($sql) === TRUE) {
         echo "<script>alert('Item deleted successfully');</script>";
        } else {
            echo "<script>alert('Error');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item and Category Management</title>
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
                    <li class="nav-item mr-3 ">
                        <a class="nav-link" href="adminmanage.php">Manage</a>
                    </li>
                    <li class="nav-item mr-3">
                        <a class="nav-link" href="adminregister.php">Account</a>
                    </li>
                    <li class="nav-item mr-3 ">
                        <a class="nav-link" href="adminlogout.php">Logout</a>
                    </li>
                    </div>
                    </div>
                  
    </nav>
    <div class="container mt-4">
        <h2>Manage Categories</h2>
        <form action="adminmanage.php" method="POST" class="mb-3">
            <div class="form-group">
                <label for="category">Category Name</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <button type="submit" class="btn btn-primary" name="action" value="add_category">Add Category</button>
        </form>

        <h2>Manage Items</h2>
        <form action="adminmanage.php" method="POST" class="mb-3" enctype="multipart/form-data">
            <div class="form-group">
                <label for="itemname">Item Name</label>
                <input type="text" class="form-control" id="itemname" name="itemname" required>
            </div>
            <div class="form-group">
                <label for="description">Item Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="img">Image</label>
                <input type="file" class="form-control" id="img" name="img">
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php
                    $result = $conn->query("SELECT id, name FROM categories");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="action" value="add_item">Add Item</button>
        </form>

        <h2>Categories</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM categories");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>
                            <form action='admindashboard.php' method='POST' class='d-inline'>
                                <input type='hidden' name='category_id' value='{$row['id']}'>
                                <button type='submit' name='action' value='delete_category' class='btn btn-danger'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Items</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT items.id, items.itemname, items.description, items.price, items.quantity, items.img, categories.name as category_name 
                                        FROM items 
                                        JOIN categories ON items.category = categories.id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['itemname']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td>";
                    if ($row['img']) {
                        echo "<img src='uploads/{$row['img']}' width='100'>";
                    }
                    echo "</td>";
                    echo "<td>{$row['category_name']}</td>";
                    echo "<td>
                            <form action='admindashboard.php' method='POST' class='d-inline'>
                                <input type='hidden' name='item_id' value='{$row['id']}'>
                                <button type='submit' name='action' value='delete_item' class='btn btn-danger'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>


<?php $conn->close(); ?>











