<?php
// Start a session to manage user login state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]);

    // Initialize separate error arrays for Username, Email, and Password
    $errorMessages = array();

    // Establish a database connection
    include('/secure_config/config.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the database to find the user
    $query = "SELECT id, username, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password_hash);

    // Check if a user was found
    if ($stmt->fetch()) {
        // Verify the user's password
        if (password_verify($password, $db_password_hash)) {
            // Password is correct
            $_SESSION["user_id"] = $user_id; // Set a session variable to indicate the user is logged in

            if ($remember_me) {
                $token = bin2hex(random_bytes(16)); // Generate a random token
                $expires = new DateTime('NOW');
                $expires->add(new DateInterval('P30D')); // Token expires after 30 days
            
                // Store the token in the database
                $stmt = $conn->prepare("INSERT INTO remember_me_tokens (user_id, token, expires) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $token, $expires->format('Y-m-d H:i:s'));
                $stmt->execute();
            
                // Store the token in a cookie
                setcookie("remember_me_token", $token, $expires->getTimestamp(), "/", "", true, true);
            }

            header("Location: /"); // Redirect to the homepage or another page
        } else {
            // Password is incorrect
            $errorMessages[0] = "Username and/or password is incorrect.";
            header("Location: login.php?error=1"); // Redirect back to the login page with an error message
        }
    } else {
        // User not found
        $errorMessages[0] = "Username and/or password is incorrect.";
        header("Location: login.php?error=2"); // Redirect back to the login page with an error message
    }

    // If there are errors, redirect back to signup.php with the error messages
    if (!empty($errorMessages)) {
        $errorMessagesString = http_build_query(array("error" => implode("<br>", $errorMessages)));
        header("Location: login.php?" . $errorMessagesString);
        exit();
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>