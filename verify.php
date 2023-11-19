<?php
// Start a session to manage user login state
session_start();

// Establish a database connection
include('/secure_config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $verificationToken = $_GET["token"];

    // Check if the verification token exists in the database
    $checkTokenQuery = "SELECT id FROM users WHERE verification_token = ?";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("s", $verificationToken);
    $checkTokenStmt->execute();
    $checkTokenStmt->store_result();

    if ($checkTokenStmt->num_rows > 0) {
        // Token found, update user account to mark email as verified
        $updateQuery = "UPDATE users SET email_verified = 1 WHERE verification_token = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $verificationToken);

        if ($updateStmt->execute()) {
            // Email verified, redirect to a success page or login page
            $_SESSION["verification_success"] = true;
            header("Location: login.php");
            exit();
        } else {
            // Error updating user account
            $_SESSION["verification_error"] = "Error updating user account.";
            header("Location: login.php");
            exit();
        }
    } else {
        // Token not found, show an error message or redirect to an error page
        $_SESSION["verification_error"] = "Invalid or expired verification token.";
        header("Location: login.php");
        exit();
    }
} else {
    // Redirect if accessed without token parameter
    header("Location: login.php");
    exit();
}

// Close database connection
$conn->close();
?>
