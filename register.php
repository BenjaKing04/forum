<?php
include('server.php');

// Check if the registration form is submitted
if (isset($_POST['reg_user'])) {
    // Get user input from the registration form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);

    // Validate the form data (perform necessary checks)
    // ...

    // Check if the passwords match
    if ($password_1 == $password_2) {
        // Hash the password for secure storage
        $password = password_hash($password_1, PASSWORD_DEFAULT);

        // Prepare the INSERT statement
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        // Execute the INSERT statement
        if (mysqli_query($conn, $query)) {
            // Registration successful
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now registered and logged in";
            header('location: index.php');
            exit;
        } else {
            // Error occurred during registration
            $errors[] = "Registration failed. Please try again later.";
        }
    } else {
        // Passwords don't match
        $errors[] = "The two passwords do not match";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="OPLogo.png">
</head>
<body>
    <div class="header">
        <h2>Register</h2>
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

    <form method="post" action="register.php">
        <?php include('errors.php'); ?>
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" autocomplete="off" ?>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" autocomplete="off "value="<?php echo $email; ?>">
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password_1" autocomplete="off">
        </div>
        <div class="input-group">
            <label>Confirm password</label>
            <input type="password" name="password_2" autocomplete>
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Register</button>
        </div>
        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </form>
</body>
</html>
