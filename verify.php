<?php
// Start a session to manage user login state
session_start();
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

    header("Location: /");
        exit();

    if ($result->num_rows === 1) {
        // Move the user from unverified table to verified users table
        $insertUserQuery = "INSERT INTO users (email) SELECT email FROM unverified WHERE email = ?";
        $insertUserStmt = $conn->prepare($insertUserQuery);
        $insertUserStmt->bind_param("s", $email);
        $insertUserStmt->execute();

        // Hash the password before storing it in the database
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the users database
        $insertUserQuery = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $insertUserStmt = $conn->prepare($insertQuery);
        $insertUserStmt->bind_param("sss", $username, $email, $password_hash);
        
        // Remove the user from unverified table after verification
        $deleteFromUnverifiedQuery = "DELETE FROM unverified WHERE email = ? AND verification_token = ?";
        $deleteFromUnverifiedStmt = $conn->prepare($deleteFromUnverifiedQuery);
        $deleteFromUnverifiedStmt->bind_param("ss", $email, $verification_token);
        $deleteFromUnverifiedStmt->execute();

        // Redirect to verification success page or homepage
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