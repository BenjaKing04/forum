<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Handle the case when the user is not logged in
    echo "You are not logged in.";
    exit();
}

// Check if the chat ID and message are provided
if (!isset($_POST['chatId']) || !isset($_POST['message'])) {
    // Handle the case when the chat ID or message is missing
    echo "Invalid request.";
    exit();
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Get the chat ID and message from the POST data
$chatId = $_POST['chatId'];
$message = $_POST['message'];

// Sanitize the input (optional, but recommended to prevent SQL injection)
$chatId = filter_var($chatId, FILTER_SANITIZE_NUMBER_INT);
$message = filter_var($message, FILTER_SANITIZE_STRING);

// Create a timestamp for the message
$timestamp = date("Y-m-d H:i:s");

// Insert the message into the database
// Replace 'localhost', 'root', '', and 'news' with your actual database credentials
$servername = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$database = 'news';

$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

if ($conn->connect_error) {
    // Handle the case when the database connection fails
    echo "Connection failed: " . $conn->connect_error;
    exit();
}

$sql = "INSERT INTO messages (chat_id, sender, message, timestamp) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $chatId, $username, $message, $timestamp);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Handle the case when the message is successfully inserted
    echo "Message sent.";
} else {
    // Handle the case when the message insertion fails
    echo "Failed to send message.";
}

$stmt->close();
$conn->close();
?>
