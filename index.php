<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "news";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="OPLogo.png">
</head>
<body>
    <style>
        
    </style>
    <div class="header">
        <h2>Home Page</h2>
    </div>

    <div class="content">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="error success">
                <h3><?php echo $_SESSION['success']; ?></h3>
            </div>
        <?php endif ?>

        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
            <div class="input-group">
                <button type="submit" class="btn1" name="logout"><a href="index.php?logout=1">Log out</a></button>
                <button type="submit" class="btn2" name="login_user"><a href="Home.php">Continue to site</a></button>
            </div>
        <?php else: ?>
            <div class="input-group">
                <button type="submit" class="btn1" name="login_user"><a href="login.php">Log in</a></button>
                <button type="submit" class="btn2" name="login_user"><a href="Home.php">Continue to site</a></button>
            </div>
        <?php endif ?>
    </div>
</body>
</html>
