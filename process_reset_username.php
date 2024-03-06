<?php
// Start a session to manage user login state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $username = $_POST["username"];
    $confirm_username = $_POST["confirm_username"];

    // Initialize separate error arrays for username
    $usernameErrors = array();

    // Check if usernames match
    if ($username !== $confirm_username) {
        $usernameErrors[] = "usernames do not match.";
    }

    // Validate username
    if (strlen($username) < 3) {
        $usernameErrors[] = "Username must be at least 3 characters long.";
    }

    if (!preg_match('/^[\x20\x23\x2D\x2E\x30-\x39\x41-\x5A\x5F\x61-\x7A]+$/', $username)) {
        $usernameErrors[] = "Username must contain only these characters: <br> A-Z a-z 0-9 Space # - _ .";
    }

    // Establish a database connection
    include('/secure_config/config.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // If there are errors, redirect back to reset_username.php with the error messages
    if (!empty($usernameErrors)) {
        $errorMessages = array(
            "username-error" => implode("<br>", $usernameErrors)
        );
        $errorMessagesString = http_build_query($errorMessages);
        header("Location: reset_username.php?token=$token&" . $errorMessagesString);
        exit();
    }

    // Update the user's username and reset token in the database
    $updateUsernameQuery = "UPDATE users SET username = ?, reset_token = NULL WHERE reset_token = ?";
    $updateUsernameStmt = $conn->prepare($updateUsernameQuery);
    $updateUsernameStmt->bind_param("ss", $username, $token);

    if ($updateUsernameStmt->execute()) {
        // Username reset successful, redirect to the login page
        header("Location: login.php?reset=success");
        exit();
    } else {
        // Username reset failed
        header("Location: reset_username.php?token=$token&error=reset-failed");
        exit();
    }

    // Close database connection
    $updateUsernameStmt->close();
    $conn->close();
} else {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
?>
