<?php
// Initialize the session - is required to check the login state.
session_start();
// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['google_email'])) {
    header('Location: account.php');
    exit;
}

$db_host = 'localhost';
$db_name = 'sociallogin';
$db_user = 'root';
$db_pass = '';
// Attempt to connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SELECT query for user account
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param('s', $_SESSION['google_email']);
$stmt->execute();
$result = $stmt->get_result();
// Fetch account details
if ($result->num_rows > 0) {
    $account = $result->fetch_assoc();
    // Retrieve session variables
    $google_loggedin = $_SESSION['google_loggedin'];
    $google_email = $account['email'];
    $google_name = $account['name'];
    $google_picture = $account['picture'];
} else {
    echo "No account found with the given ID.";
}
$stmt->close();

// Prepare and execute SELECT query for order history
$order_stmt = $conn->prepare("SELECT order_id, order_date, final_amount FROM orders WHERE email = ?");
$order_stmt->bind_param('s', $_SESSION['google_email']);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

// Fetch order details for each order
$order_details = [];
while ($order = $order_result->fetch_assoc()) {
    $order_id = $order['order_id'];
    $details_stmt = $conn->prepare("SELECT product_code, product_name, quantity, unit_price, total_price FROM order_details WHERE order_id = ?");
    $details_stmt->bind_param('i', $order_id);
    $details_stmt->execute();
    $details_result = $details_stmt->get_result();
    $products = [];
    while ($product = $details_result->fetch_assoc()) {
        $products[] = $product;
    }
    $order['products'] = $products;
    $order_details[] = $order;
    $details_stmt->close();
}

// Close database connection
$conn->close();
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <title>User Profile-Rabbit(Ecommerce)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="profile.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <div class="container">
        <h1>My Account</h1>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="4.php"><i class="fas fa-shopping-bag"></i> Shop</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section class="profile-info">
        <div class="container">
            <div class="profile-header">
                <div class="profile-picture">
                    <i><img src="<?= htmlspecialchars($google_picture) ?>" alt="Profile Picture"></i>
                </div>
                <div class="profile-details">
                    <h2><?= htmlspecialchars($google_name) ?></h2>
                    <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($google_email) ?></p>
                    <!--<p><i class="fas fa-map-marker-alt"></i> 123 Main St, Cityville</p>-->
                    <a href="update_profile.php" class="edit-profile-btn"><i class="fas fa-edit"></i> Edit Profile</a>
                </div>
            </div>
        </div>
    </section>

    <section class="order-history">
        <div class="container">
            <h2><i class="fas fa-history"></i> Order History</h2>
            <?php foreach ($order_details as $order): ?>
                <div class="order-summary">
                    <br>
                    <h3>Order ID: <?= htmlspecialchars($order['order_id']) ?></h3>
                    <p>Date: <?= htmlspecialchars($order['order_date']) ?></p>
                    <p>Total Amount: ₹ <?= htmlspecialchars(number_format($order['final_amount'], 2)) ?></p>
                    <h4>Products:</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['products'] as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['product_code']) ?></td>
                                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                                    <td>₹ <?= htmlspecialchars(number_format($product['unit_price'], 2)) ?></td>
                                    <td>₹ <?= htmlspecialchars(number_format($product['total_price'], 2)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
$order_stmt->close();
?>
</main>
</body>
</html>
