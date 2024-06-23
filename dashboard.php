<?php
$conn = mysqli_connect("localhost", "root", "", "db_shop");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$categoryQuery = "SELECT id, name FROM categories ORDER BY name";
$itemQuery = "SELECT * FROM items WHERE category = ?";

$preparedStatement = mysqli_prepare($conn, $categoryQuery);
if (!$preparedStatement) {
    echo "Error preparing category query: " . mysqli_error($conn);
    exit();
}
mysqli_stmt_execute($preparedStatement);
$categoryResult = mysqli_stmt_get_result($preparedStatement);

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $selectedCategory = $_GET['category'];
    $itemStatement = mysqli_prepare($conn, $itemQuery);
    if (!$itemStatement) {
        echo "Error preparing item query: " . mysqli_error($conn);
        exit();
    }
    mysqli_stmt_bind_param($itemStatement, "i", $selectedCategory);
    mysqli_stmt_execute($itemStatement);
    $items = mysqli_stmt_get_result($itemStatement);
} else {
    $itemQueryAll = "SELECT * FROM items ORDER BY itemname";
    $itemResultAll = mysqli_query($conn, $itemQueryAll);

    if (!$itemResultAll) {
        echo "Error fetching items: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($itemResultAll) > 0) {
        $items = $itemResultAll;
    } else {
        echo "No items found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px #1C3144;
            border-radius:20px;
        }

        @media (max-width: 992px) {
            .navbar-expand-lg .navbar-collapse {
                flex-direction: column;
                align-items: flex-start;
            }
            .navbar-nav {
                margin-top: 10px; 
            }
            .navbar-nav .nav-item {
                margin: 0;
                text-align: center;
            }
            .navbar-nav .nav-link {
                padding: .5rem 1rem;
                margin: .25rem 0;
            }
        }
        
        footer {
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        .footer-text {
            color: #6c757d;
        }

        .navbar-nav .nav-link {
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: #0056b3;
        }

        .carousel-inner img {
            width: 100%;
            height: auto;
        }

        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        .carousel {
            width: 100vw;
            margin-left: calc(-50vw + 50%);
        }

        .carousel-item img {
            width: 100vw;
            transition: opacity 0.5s ease;
        }
        .custom-btn {
            border: 2px solid transparent;
            border-radius:20px;
            transition: border-color 0.8s ease;
        }

        .custom-btn:hover {
            border-color: #1C3144;
            background-color: white;
            color:black;
            border-radius:20px;
        }
        .navbar.sticky-top {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            
        }

        body {
            padding-top: 56px; 
        }

        @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                .animated {
                    animation: fadeInUp 1s ease-out;
                }

                .cart-section {
                    background-color: #162e46; 
                    padding: 20px;
                    border-radius: 10px;
                    color: #fff; 
                }

                .cart-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }

                .cart-content {
                    
                }

    </style>
</head>
<body class="animated">
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
    <div class="container-fluid mt-1 p-0">
        <div id="carouselExample" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="banner/img1.png" class="d-block w-100" alt="Image 1">
                </div>
                <div class="carousel-item">
                    <img src="banner/img3.png" class="d-block w-100" alt="Image 2">
                </div>
                <div class="carousel-item">
                    <img src="banner/img2.png" class="d-block w-100" alt="Image 3">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="container pt-4">
    <div class="row">
        <div class="col-md-3">
            <form action="" method="GET">
                <div class="form-group">
                    <label for="category">Select Category:</label>
                    <select class="form-control" id="category" name="category" onchange="this.form.submit()">
                        <option value="">All Items</option>
                        <?php 
                        $categoryResult = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
                        if ($categoryResult) {
                            while ($category = mysqli_fetch_assoc($categoryResult)) :
                        ?>
                        <option value="<?php echo $category['id']; ?>" <?php if(isset($_GET['category']) && $_GET['category'] == $category['id']) echo 'selected'; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                        <?php endwhile; ?>
                        <?php mysqli_free_result($categoryResult); ?>
                        <?php } else {
                            echo "Error fetching categories: " . mysqli_error($conn);
                        }
                        ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-9">
            <div class="row">
                <?php 
                while ($item = mysqli_fetch_assoc($items)) {
                ?>
                <div class="col-12 col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 custom-border-radius">
                        <img src="uploads/<?php echo $item['img']; ?>" class="card-img-top" alt="...">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['itemname']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($item['description']); ?></h6>
                            <p class="card-text">Price: ₱ <?php echo number_format($item['price'], 2); ?></p>
                            <p class="card-text">Qty: 
                                <?php 
                                if ($item["quantity"] > 0) {
                                    echo $item["quantity"];
                                } else {
                                 echo '<span class="text-danger">Sold out</span>';
                                
                                }
                                ?>
                            </p>
                            <?php if ($item["quantity"] > 0) { ?>
                                <a href="addcart.php?id=<?php echo $item['id']; ?>" class="btn btn-primary mt-auto custom-btn">Add to Cart</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
                }
                ?>
            </div>
        </div>
    </div>
</div>

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="footer-text">© 2024 Benta.ph. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>