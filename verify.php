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
        // Fetch the user details
        $userDetails = $result->fetch_assoc();

        // Extract username and password hash
        $username = $userDetails['username'];
        $password_hash = $userDetails['password_hash'];

        // Update the user's verification status to mark it as verified
        $updateQuery = "UPDATE users SET is_verified = 1 WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $email);
        $updateStmt->execute();

        $_SESSION["user_id"] = $userDetails["id"]; // Set a session variable to indicate the user is logged in
        header("Location: /");
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