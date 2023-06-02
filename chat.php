<?php
session_start();

// Redirect the user to the login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Get the chat ID from the URL parameter
if (!isset($_GET['id'])) {
    header("Location: Forum.php");
    exit();
}
$chatId = $_GET['id'];

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

// Fetch the chat title from the database
$sql = "SELECT title FROM chats WHERE id = $chatId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $chatTitle = $row['title'];
} else {
    $chatTitle = "Unknown Chat";
}

// Function to get the messages for a specific chat
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

// Function to display the messages in a chat-like format
// Function to display the messages in a chat-like format
function displayMessages($messages) {
    foreach ($messages as $message) {
        $sender = $message['sender'];
        $messageContent = $message['message'];
        $timestamp = $message['timestamp'];

        echo "<p><strong style='color: #40C3FF;'>$sender:</strong> <span class='message-content'>$messageContent</span></p>";
        echo "<span class='timestamp'>$timestamp</span>";
        echo "<hr>";
    }
}

// Retrieve and display the messages for the chat
$messages = getChatMessages($conn, $chatId);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <link rel="icon" href="OPLogo.png">
    <style>
        .chat-container {
            height: 319px;
            overflow-y: auto;
            border: 2px solid #40C3FF;
            padding: 10px;
            word-break: break-all;
            border-radius: 4px;
        }

        .chat-container p {
            color: red; /*Sets the chat text color*/
        }

        .chat-container p strong {
            color: #40C3FF; /*Sets the user's chat color */
        }

        .message-form {
            margin-top: 10px;
        }

        .message-form input[type="text"] {
            width: 70%;
            padding: 3px;
            height: 26px;
        }

        .message-form button[type="submit"] {
            padding: 2px 5px;
        }

        .timestamp {
            color: #888;
            font-size: 12px;
        }

        .sender {
            color: #FF0000; /* Set the desired color for the sender text */
            font-weight: bold; /* Add font-weight property if desired */
        }
        .message-content {
            color: red;
        }

        .buttons {
            margin-top: -10px;
        }

        .buttons form,
        .buttons button {
            display: inline-block;
            margin-right: 10px;
        }

        .buttons form input[type="hidden"] {
            display: none;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #191919;
        }

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
            Color: #3FC5FA;
            font-size: 24px; /* Update the font size */
        }

        h1 {
            margin-top: 3px;
        }

        h2 {
            text-align: center;
            margin-top: 2px;
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
            outline: none; /* Remove outline on button */
        }

        ul {
            list-style-type: none;
            padding: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-size: 16px; /* Add font-size property */
        }

        li {
            margin-bottom: 10px;
        }

        a {
            color: #40C3FF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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

        form, .content {
            color: grey;
            max-width: 100%; /* Update the width to 90% */
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
            width: 100%; /* Update the width to 100% */
            padding: 5px 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid gray;
        }

        .btn {
            padding: 10px;
            font-size: 15px;
            color: white;
            background: #5F9EA0;
            border: none;
            border-radius: 5px;
        }
        

    </style>
</head>
<body>
    <h1><?php echo $chatTitle; ?></h1>

    <!-- Add the form to clear chat history -->
    <div class="buttons">
        <form action="clearChat.php" method="get">
            <input type="hidden" name="id" value="<?php echo $chatId; ?>">
            <button type="submit">Clear Chat History</button>
        </form>
        <form>
            <button type="submit">Back</button>
        </form>
    </div>

    

    <div class="chat-container" id="chatMessages">
        <?php displayMessages($messages); ?>
    </div>

    <form class="message-form" id="messageForm" action="sendMessage.php" method="post">
        <input type="hidden" name="chatId" value="<?php echo $chatId; ?>">
        <input type="hidden" name="sender" value="<?php echo $username; ?>">
        <input type="text" name="message" autocomplete="off" placeholder="Enter your message">
        <button type="submit">Send</button>
    </form>

    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to refresh the chat messages
        function refreshMessages() {
            $.ajax({
                url: "getMessages.php?chatId=<?php echo $chatId; ?>",
                success: function (data) {
                    $("#chatMessages").html(data);
                    // Scroll to the bottom of the chat container
                    $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);

                    //preserve the sender's name color
                    $(".chat-container p strong").css("color", "#40C3FF");

                    // Preserve the chat message color
                    $(".chat-container p:not(:has(strong))").css("color", "red");
                }
            });
        }

        $(document).ready(function () {
            // Submit the message form using AJAX
            $("#messageForm").submit(function (e) {
                e.preventDefault();

                // Trim the message content and check if it's empty
                var messageContent = $(this).find("input[name='message']").val().trim();
                if (messageContent === "") {
                    // Show an error message or perform any desired action
                    alert("Please enter a message.");
                    return;
                }

                // If the message is not empty, proceed with sending it
                $.ajax({
                    url: "sendMessage.php",
                    method: "post",
                    data: $(this).serialize(),
                    success: function () {
                        $("#messageForm input[name='message']").val("");
                        refreshMessages();
                    }
                });
            });

            // Disable form submission on Enter key press
            $("#messageForm input[name='message']").keydown(function (e) {
                if (e.keyCode === 13 && e.shiftKey === false) {
                    e.preventDefault();
                    $("#messageForm").submit();
                }
            });
        });
    </script>

    <script>
        

        // Handle the back button click event
        $("#backButton").click(function () {
            window.history.back();
        });
    </script>

</body>
</html>