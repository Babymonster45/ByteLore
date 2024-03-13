<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

// Establish a database connection
include('/secure_config/config.php');

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Check if the provided email and token match a record in the unverified table
    $checkTokenQuery = "SELECT * FROM unverified WHERE email = ? AND verification_token = ?";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("ss", $email, $token);
    $checkTokenStmt->execute();
    $result = $checkTokenStmt->get_result();

    if ($result->num_rows === 1) {
        // Move the user from unverified table to verified users table
        $moveUserQuery = "INSERT INTO users (username, email, password_hash) SELECT username, email, password_hash FROM unverified WHERE email = ?";
        $moveUserStmt = $conn->prepare($moveUserQuery);
        $moveUserStmt->bind_param("s", $email);
        $moveUserStmt->execute();

        if ($moveUserStmt->affected_rows > 0) {
            // Remove the user from unverified table based on email
            $deleteFromUnverifiedQuery = "DELETE FROM unverified WHERE email = ?";
            $deleteFromUnverifiedStmt = $conn->prepare($deleteFromUnverifiedQuery);
            $deleteFromUnverifiedStmt->bind_param("s", $email);
            $deleteFromUnverifiedStmt->execute();

            $_SESSION["user_id"] = $conn->insert_id; // Set a session variable to indicate the user is logged in
            header("Location: /"); // Redirect to homepage
            exit();
        } else {
            // Error moving user to the users table
            header("Location: verification_failure.php");
            exit();
        }
    } else {
        // Invalid verification link or email not found in unverified table
        header("Location: verification_failure.php");
        exit();
    }
}
?>