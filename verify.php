<?php
// Start a session to manage user login state
session_start();
// Establish a database connection
include('/secure_config/config.php');

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Check if the provided email exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the user details
        $userDetails = $result->fetch_assoc();
        $verification_token = $userDetails['verification_token'];
        $is_verified = $userDetails['is_verified'];

        if ($is_verified == 0 && $verification_token === $token) {
            // Valid verification link; update the user's verification status
            $updateQuery = "UPDATE users SET is_verified = 1 WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("s", $email);
            $updateStmt->execute();

            $_SESSION["user_id"] = $userDetails["id"]; // Set a session variable to indicate the user is logged in
            header("Location: /");
            exit();
        }
    }
}

// Invalid verification link
header("Location: verification_failure.php");
exit();
?>