<?php
// Get the chat ID from the URL parameter
if (!isset($_GET['chatId'])) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}
$chatId = $_GET['chatId'];

// Create a MySQLi connection
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'news';
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

// Function to retrieve the chat messages from the database
function getChatMessages($conn, $chatId) {
    $messages = array();

    $sql = "SELECT * FROM messages WHERE chat_id = $chatId ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }

    return $messages;
}

// Retrieve the chat messages
$messages = getChatMessages($conn, $chatId);

// Generate HTML for the chat messages
$html = '';
foreach ($messages as $message) {
    $sender = $message['sender'];
    $messageContent = $message['message'];
    $timestamp = $message['timestamp'];

    $html .= "<p><strong>$sender:</strong> $messageContent</p>";
    $html .= "<span class='timestamp'>$timestamp</span>";
    $html .= "<hr>";
}

// Close the database connection
$conn->close();

// Return the generated HTML
echo $html;
?>
