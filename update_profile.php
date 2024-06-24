<?php
// Initialize the session
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

// Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Handle file upload
    if ($profile_picture) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully to $target_file.<br>"; // Debugging line
        } else {
            echo "Error uploading file.<br>"; // Debugging line
        }
    } else {
        $target_file = $_POST['current_picture'];
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, picture = ? WHERE email = ?");
    $stmt->bind_param('sss', $name, $target_file, $_SESSION['google_email']);
    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['picture'] = $target_file;
        header('Location: profile.php');
        exit;
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve user details
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param('s', $_SESSION['google_email']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Rabbit (Ecommerce)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="update_profile.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Update Profile</h1>
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
    <section class="update-profile">
        <div class="container">
            <form action="update_profile.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                    <input type="hidden" name="current_picture" value="<?= htmlspecialchars($user['picture']) ?>">
                    <?php if ($user['picture']): ?>
                        <img src="<?= htmlspecialchars($user['picture']) ?>" alt="Profile Picture" class="current-picture">
                    <?php else: ?>
                        <p>No profile picture set.</p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="update-btn"><i class="fas fa-save"></i> Update Profile</button>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
