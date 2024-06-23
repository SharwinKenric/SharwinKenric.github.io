<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for animations */
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
                    <li class="nav-item mr-3 active">
                        <a class="nav-link" href="About_Us.php">About Us</a>
                    </li>
                    <li class="nav-item mr-3 dropdown">
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

    <div class="container mt-4">
        <div class="card mb-4 animated">
            <div class="card-body">
                <h1 class="card-title">About Us</h1>
                <p class="card-text"><h3>Welcome to Benta.ph – Your Ultimate Online Shopping Destination!</h3></p>
            </div>
        </div>

        <div class="card mb-4 animated">
            <div class="card-body">
                <p class="card-text">At <span>Benta.ph</span>, we believe that everyone deserves access to quality products without breaking the bank. Our mission is to bring you a diverse range of affordable items that cater to your everyday needs and special occasions alike. Whether you're shopping for the latest fashion trends, home essentials, electronics, or unique gifts, Benta.ph is your go-to online store.</p>
            </div>
        </div>

        <div class="card mb-4 animated">
            <div class="card-body">
                <h3 class="card-title">Our Goal: Affordability Meets Quality</h3>
                <p class="card-text">Benta.ph is committed to offering the best prices on the market without compromising on quality. We understand the importance of value for money, and our goal is to make shopping a delightful experience by providing high-quality products at prices you can afford. By sourcing directly from manufacturers and trusted suppliers, we ensure that our customers get the best deals available.</p>
            </div>
        </div>

        <div class="card mb-4 animated">
            <div class="card-body">
                <h3 class="card-title">Why Shop with Benta.ph?</h3>
                <ul class="card-text">
                    <li>Wide Selection: From trendy apparel and accessories to household gadgets and tech innovations, our extensive catalog has something for everyone.</li>
                    <li>Customer Satisfaction: Your satisfaction is our top priority. We strive to provide excellent customer service, fast shipping, and a hassle-free return policy.</li>
                    <li>Secure Shopping: Shop with confidence knowing that your transactions are safe and secure with our advanced encryption technology.</li>
                    <li>Community Focus: We are proud to support local businesses and artisans by featuring their products on our platform, promoting sustainable and ethical shopping practices.</li>
                </ul>
                <p>Join the Benta.ph family today and experience the joy of finding great deals on the products you love. Happy shopping!</p>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="footer-text">© 2024 Benta.ph. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>