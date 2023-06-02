<?php
session_start();

// Log out the user
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("Location: Home.php");
    exit();
}

$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>The best written story ever</title>
    <link rel="stylesheet" href="styleTeam.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Goblin+One&display=swap" rel="stylesheet">
    <link rel="icon" href="OPLogo.png">

</head>

<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Goblin+One&display=swap');
    </style>
    <!-- Header -->
    <section class="banner" id="sec">.
        <header>
            <a href="#" class="logo">One Piece</a>
            <div id="toggle" onclick="toggle()"></div>
        </header>
        <div class="content">
            <h2>Team<br>This is <span>Team Page </span></h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate.</p>
            <a href="#">Know More</a>
        </div>
        <ul class="sci">
            <li><a
                    href="https://m.facebook.com/benjamin.olsson.754?eav=AfaMYRcp1zCxAkYJL7yuJA46eE1rUkbe8NQCppp0bXgvkY7m6OA6FIJQhY0x_tkxKq0&paipv=0"><img
                        src="facebook.png"></a></li>
            <li><a href="https://twitter.com/BenjaKi68727302"><img src="twitter.png"></a></li>
            <li><a href="https://www.instagram.com/benja_king04/"><img src="instagram.png"></a></li>
        </ul>
    </section>
    <div id="navigation">
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="About.php">About</a></li>
            <li><a href="#">Team</a></li>
            <li><a href="Contact.php">Contact</a></li>
            <li><a href="Forum.php">Forum</a></li>
            <?php if ($isLoggedIn) : ?>
        <li><a href="Home.php?logout=1">Log out</a></li>
      <?php else : ?>
        <li><a href="login.php">Log in</a></li>
      <?php endif; ?>
        </ul>
    </div>
    <script type="text/javascript">
        function toggle() {
            var sec = document.getElementById('sec');
            var nav = document.getElementById('navigation');
            sec.classList.toggle('active')
            nav.classList.toggle('active')
        }
    </script>
</body>

</html>