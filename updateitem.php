<?php
$conn = new mysqli("localhost", "root", "", "db_shop");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Items</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="admindashboard.php"><h3>Benta.ph</h3></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-3 ">
                        <a class="nav-link" href="updateitems.php">Dashboard</a>
                    </li>
                    </div>
                    </div>
                  
    </nav>

    </div>
    <div class="container mt-4 mb-4">
        <h2>Update Item</h2>
        <div class="card mb-4">
            <div class="card-header">Update Item</div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="action" value="update">
                    <div class="form-group">
                        <label for="id">Item ID</label>
                        <input type="text" class="form-control" id="id" name="id" >
                    </div>
                    <div class="form-group">
                        <label for="itemname">Item Name</label>
                        <input type="text" class="form-control" id="itemname" name="itemname" >
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" ></textarea>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" name="price" rows="3" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" rows="3" required>
                    </div>
                    <div class="form-group">
                        <label for="img">Image</label>
                        <input type="File" class="form-control" id="img" name="img" >
                    </div>
                   
                    <button type="submit" class="btn btn-primary">Update Item</button>

                    <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
                   
                            $id = isset($_POST['id']) ? $_POST['id'] : '';
                            $itemname = isset($_POST['itemname']) ? $_POST['itemname'] : '';
                            $description = isset($_POST['description']) ? $_POST['description'] : '';
                            $price = isset($_POST['price']) ? $_POST['price'] : '';
                            $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
                            $img = isset($_POST['img']) ? $_POST['img'] : '';
                            

                            if (empty($id) || empty($itemname) || empty($description)|| empty($price) || empty($quantity) || empty($img)) {
                                echo "<script>alert('Field cannot be empty.');</script>";
                            } else {
                                $query = "UPDATE items SET itemname = '$itemname', description = '$description' 
                                , price = '$price', quantity = '$quantity', img = '$img'
                                WHERE id = $id";
                                $success = mysqli_query($conn, $query);
                                if ($success) {
                                    echo "<script>alert('Item updated successfully.');</script>";
                                } else {
                                    echo "<script>alert('Update unsuccessful.');</script>";
                                }
                            }
                        }
                        ?>
                </form>
            </div>
            
        </div>
    </div>
    <div class="container mt-4 mb-4">
        <h2>Update Category</h2>
        <div class="card mb-4">
            <div class="card-header">Update Category</div>
            <div class="card-body">

                    <form action="" method="POST">
                        <input type="hidden" name="action" value="update_category">
                        <div class="form-group">
                            <label for="category_id">Category ID</label>
                            <input type="text" class="form-control" id="category_id" name="category_id " >
                        </div>
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" >
                        </div>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                if (isset($_POST['action']) && $_POST['action'] == 'update') {
                                    // Code for updating item
                                } elseif (isset($_POST['action']) && $_POST['action'] == 'update_category') {
                                    $id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
                                    $name = isset($_POST['category_name']) ? $_POST['category_name'] : '';
                                    
                                    if (empty($id) || empty($name)) {
                                        echo "<script>alert('Field cannot be empty.');</script>";
                                    } else {
                                        $query = "UPDATE categories SET name = '$name' WHERE id = $id";
                                        $success = mysqli_query($conn, $query);
                                        if ($success) {
                                            echo "<script>alert('Category updated successfully.');</script>";
                                        } else {
                                            echo "<script>alert('Update unsuccessful.');</script>";
                                        }
                                    }
                                }
                            }
                            ?>
                                
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    
     
</body>
</html>