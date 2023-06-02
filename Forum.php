<?php
session_start();

// Function to retrieve the MySQL credentials
function getUserDatabaseCredentials($username) {
    $defaultCredentials = array(
        'servername' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'news'
    );

    return $defaultCredentials;
}

// Get the MySQL credentials
$userCredentials = getUserDatabaseCredentials('');

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

// Handle the delete request for a chat
if (isset($_GET['delete'])) {
    $chatId = $_GET['delete'];

    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        $loggedInUser = $_SESSION['username'];

        // Retrieve the chat owner from the database
        $stmt = $conn->prepare("SELECT owner FROM chats WHERE id = ?");
        $stmt->bind_param("i", $chatId);
        $stmt->execute();
        $stmt->bind_result($chatOwner);

        // Fetch the result
        if ($stmt->fetch()) {
            // Check if the user performing the delete action is the same as the chat owner or special user
            if ($loggedInUser === $chatOwner || $loggedInUser === 'admin') {
                // Close the previous statement and result set
                $stmt->close();

                // Prepare the SQL statement to delete the chat
                $stmt = $conn->prepare("DELETE FROM chats WHERE id = ?");
                $stmt->bind_param("i", $chatId);

                // Execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect back to the Forum page after successful deletion
                    header("Location: Forum.php");
                    exit();
                } else {
                    $deleteError = "Error deleting chat: " . $stmt->error;
                }
            } else {
                $deleteError = "<span class='error-message'>You do not have permission to delete this chat.</span>";
            }
        } else {
            $deleteError = "Chat not found.";
        }

        $stmt->close();
    } else {
        $deleteError = "You must be logged in to delete a chat.";
    }
}

// Handle the create request for a new chat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chatTitle = $_POST['chat_title'];

    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        $chatOwner = $_SESSION['username'];

        // Prepare the SQL statement to insert the new chat into the database
        $stmt = $conn->prepare("INSERT INTO chats (title, owner) VALUES (?, ?)");
        $stmt->bind_param("ss", $chatTitle, $chatOwner);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Redirect back to the Forum page after successful creation
            header("Location: Forum.php");
            exit();
        } else {
            $createError = "Error creating chat: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $createError = "<span class='error-message'>You must be logged in to create a chat.</span>";
    }
}

// Fetch existing chats from the database
$sql = "SELECT * FROM chats";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="icon" href="OPLogo.png">
    <link rel="stylesheet" href="styleForum.css">
    <style>
        body#forum {
  font-family: Arial, sans-serif;
  background-color: #191919;}

.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
}

h1,
h2 {
  text-align: center;
  margin-top: 0;
  color: #3FC5FA;
  font-size: 24px;
}

h2 {
  text-align: center;
  margin: 2px;
  color: #3FC5FA;
}

h2.underline {
  position: relative;
}

h2.underline::after {
  content: "";
  position: absolute;
  bottom: -5px;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  height: 2px;
  background-color: #3FC5FA;
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}

input[type="text"] {
  padding: 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid #ccc;
  outline: none;
  width: 100%;
  margin-bottom: 10px;
}

button[type="submit"] {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #40C3FF;
  color: #ffffff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  outline: none;
}

ul {
  list-style-type: none;
  padding: 5px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  font-size: 16px;
}

li {
  margin: 5px;
  color: red;
}

a {
  color: #40C3FF;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.delete-button {
  display: inline-block;
  padding: 5px 10px;
  font-size: 14px;
  background-color: black;
  color: #ffffff;
  border: 2px solid #40C3FF;
  border-radius: 5px;
  cursor: pointer;
  text-decoration: none;
}

* {
  margin: 0px;
  padding: 0px;
}

body {
  font-size: 120%;
  background: #191919;
  padding-left: 0;
  padding-right: 0;
  padding-top: 0;
  padding-bottom: 0;
  font-family: 'Goblin One', cursive;
}

.header {
  width: 30%;
  margin: 50px auto 0px;
  color: black;
  background: #40C3FF;
  text-align: center;
  border: 1px solid #40C3FF;
  border-bottom: none;
  border-radius: 10px 10px 0px 0px;
  padding: 20px;
}

form,
.content {
  color: grey;
  max-width: 100%;
  margin: 0px auto;
  padding: 15px;
  border: 2px solid #40C3FF;
  background: black;
  border-radius: 10px;
}

.input-group {
  margin: 10px 0px 10px 0px;
}

.input-group label {
  display: block;
  text-align: left;
  margin: 3px;
}

.input-group input {
  height: 30px;
  width: 93%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid grey;
}

.input-group select {
  height: 38px;
  width: 100%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid grey;
}

.input-group button {
  padding: 10px;
  font-size: 15px;
  background-color: #40C3FF;
  color: #ffffff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.error {
  width: 92%;
  margin: 0px auto;
  padding: 10px;
  border: 1px solid #a94442;
  color: #a94442;
  background: #f2dede;
  border-radius: 5px;
  text-align: left;
}

.success {
  color: #3c763d;
  background: #dff0d8;
  border: 1px solid #3c763d;
  margin-bottom: 20px;
}

.error-message {
    color: red;
}
}

    </style>
</head>

<body id="forum">
    <?php if (isset($deleteError)) { ?>
        <p class="error-message"><?php echo $deleteError; ?></p>
    <?php } ?>
    <div class="container">
        <h1>Welcome to the Forum!</h1>

        <h2>Create a New Chat</h2>
        <form method="post" action="Forum.php">
    <?php if (isset($createError)) { ?>
        <p class="error-message"><?php echo $createError; ?></p>
    <?php } ?>
    <input type="text" name="chat_title" autocomplete="off" placeholder="Chat Title" required>
    <button type="submit">Create Chat</button>
</form>

        <h2 class="underline">Existing Chats</h2>
        <div class="chats-container">
            <ul>
                <?php
                if ($result->num_rows > 0) {
                    // Display each chat as a list item
                    while ($row = $result->fetch_assoc()) {
                        $chatId = $row['id'];
                        $chatTitle = $row['title'];
                        echo "<li><a href='chat.php?id=$chatId'>$chatTitle</a> <a href='#' onclick='deleteChat($chatId)' class='delete-button'>Delete</a></li>";
                    }
                } else {
                    echo "<li>No chats available</li>";
                }
                ?>
            </ul>
        </div>

        <form action="Home.php">
            <button type="submit">Back</button>
        </form>
    </div>

    <script>
        function deleteChat(chatId) {
            if (confirm("Are you sure you want to delete this chat?")) {
                window.location.href = "Forum.php?delete=" + chatId;
            }
        }
    </script>
</body>

</html>
