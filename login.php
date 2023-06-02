<?php
session_start();
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

// Initialize variables
$errors = array();

// Create admin user
$adminUsername = 'admin';
$adminPassword = 'admin_password'; // Replace with the desired admin password

$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

//$sql = "INSERT INTO users (username, password) VALUES ('$adminUsername', '$hashedPassword')";
//if ($conn->query($sql) === TRUE) {
//    echo "Admin user created successfully";
//} else {
//    echo "Error creating admin user: " . $conn->error;
//}

// Log in user
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if the username and password match in the database
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Check if the user is the admin
            if ($username === 'admin') {
                // Login as admin
                $_SESSION['username'] = $username;
                $_SESSION['admin'] = true;
                $_SESSION['success'] = "You are now logged in as admin";
                header('location: index.php');
                exit;
            } else {
                // Login successful for regular user
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php');
                exit;
            }
        } else {
            // Login failed: invalid password
            $errors[] = "Invalid password or password";
        }
    } else {
        // Login failed: user not found
        $errors[] = "Invalid username or password";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="OPLogo.png">
    <script src="discord-bot.js"></script>
</head>
<body>
    <div class="header">
        <h2>Login</h2>
    </div>
    <style>
        body {
            background: #191919;
        }

        .header {
            background: #40C3FF;
            color: black;
            border-color: #40C3FF;
        }

        form {
            background: black;
            border-color: #40C3FF;
            color: grey;
        }

        .btn {
            background-color: black;
            color: #40C3FF;
        }

        a {
            color: #40C3FF;
        }
    </style>
    <form method="post" action="login.php">
        <?php include('errors.php'); ?>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="login_user">Login</button>
        </div>
        <p>
            Not yet a member? <a href="register.php">Sign up</a>
        </p>
    </form>
</body>
</html>
