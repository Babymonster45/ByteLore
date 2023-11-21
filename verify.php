<?php
// Start a session to manage user login state
session_start();
// Establish a database connection
include('/secure_config/config.php');

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Check if the provided email and token match a record in the database
    $checkTokenQuery = "SELECT * FROM users WHERE email = ? AND verification_token = ? AND is_verified = 0";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("ss", $email, $token);
    $checkTokenStmt->execute();
    $result = $checkTokenStmt->get_result();

    if ($result->num_rows === 1) {
        // If email and token match, create the user account and mark it as verified
        $updateQuery = "INSERT INTO users (username, email, password_hash, is_verified) VALUES (?, ?, ?, 1)";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sss", $username, $email, $password_hash); // Use values from the sign-up process
        $updateStmt->execute();

        $_SESSION["user_id"] = $insertStmt->insert_id; // Set a session variable to indicate the user is logged in
        header("Location: verification_sent.php");
        exit();
    } else {
        // Invalid verification link
        header("Location: verification_failure.php");
        exit();
    }
} else {
    // Handle invalid verification requests
    header("Location: verification_failure.php");
    exit();
}
?>
