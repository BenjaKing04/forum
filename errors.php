<?php  if (count($errors) > 0) : ?>
<div class="error">
    <?php foreach ($errors as $error) : ?>
    <p><?php echo $error ?></p>
    <?php endforeach ?>
</div>
<?php  endif ?>
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
?>