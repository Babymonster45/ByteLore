<?php
// Establish a database connection
include('/secure_config/config.php');

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Fetch user based on email and token from the database
    $checkTokenQuery = "SELECT * FROM users WHERE email = ? AND verification_token = ? AND is_verified = 0";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("ss", $email, $token);
    $checkTokenStmt->execute();
    $result = $checkTokenStmt->get_result();

    if ($result->num_rows === 1) {
        // Update the user's status to 'verified' in the database
        $updateQuery = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $email);
        $updateStmt->execute();

        // Redirect to success page
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
