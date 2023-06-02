<?php
session_start();

// Redirect the user to the login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Function to retrieve the MySQL credentials for a user
function getUserDatabaseCredentials($username) {
    // Query your database or any other method to fetch the user's MySQL credentials
    // Here's an example of how you can define the credentials for a few users
    $users = array(
        'user1' => array(
            'servername' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'news'
        ),
        'user2' => array(
            'servername' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'news'
        ),
        'admin' => array(
            'servername' => 'localhost',
            'username' => 'admin',
            'password' => 'adminpassword',
            'database' => 'news'
        ),
        'default' => array(
            'servername' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'news'
        ),
        // Add more users and their credentials as needed
    );

    // Check if the user exists in the array
    if (array_key_exists($username, $users)) {
        return $users[$username];
    } else {
        // Return default credentials or handle the case when the user doesn't have specific credentials
        return $users['default'];
    }
}

// Get the chat ID from the URL parameter
if (!isset($_GET['id'])) {
    header("Location: Forum.php");
    exit();
}
$chatId = $_GET['id'];

// Get the MySQL credentials for the user
$userCredentials = getUserDatabaseCredentials($username);

// Extract the credentials from the array
$servername = $userCredentials['servername'];
$dbUsername = $userCredentials['username'];
$dbPassword = $userCredentials['password'];
$database = $userCredentials['database'];

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to clear the chat history
function clearChatHistory($conn, $chatId) {
    // Add your code here to clear the chat history for the specified chat ID
    // For example, you can run a DELETE query to remove all messages for the chat
    $sql = "DELETE FROM messages WHERE chat_id = $chatId";
    $result = $conn->query($sql);

    if ($result) {
        echo "Chat history cleared successfully.";
    } else {
        echo "Failed to clear chat history.";
    }
}

// Call the function to clear the chat history
clearChatHistory($conn, $chatId);

?>
 