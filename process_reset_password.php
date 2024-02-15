<?php
// Start a session to manage user login state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $password = $_POST["password"];

    // Initialize separate error arrays for password
    $passwordErrors = array();

    // Validate password
    if (strlen($password) < 8) {
        $passwordErrors[] = "Password must be at least 8 characters long.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $passwordErrors[] = "Password must contain at least 1 number.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $passwordErrors[] = "Password must contain at least 1 uppercase character.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $passwordErrors[] = "Password must contain at least 1 lowercase character.";
    }

    if (!preg_match('/[\x21\x23\x24\x26\x28-\x2B\x2D\x3D\x3F\x40\x5B\x5D\x7E]/', $password)) {
        $passwordErrors[] = "Password must contain at least 1 special character.<br> Characters include: ! # $ & ( ) * + - = ? @ [ ] ~ ";
    }

    // Establish a database connection
    include('/secure_config/config.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the token is valid and not expired
    $checkTokenQuery = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expiration > NOW()";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("s", $token);
    $checkTokenStmt->execute();
    $checkTokenStmt->store_result();

    if ($checkTokenStmt->num_rows === 0) {
        // Token is either invalid or expired
        header("Location: reset_password.php?token=$token&error=invalid-token");
        exit();
    }

    // If there are errors, redirect back to reset_password.php with the error messages
    if (!empty($passwordErrors)) {
        $errorMessages = array(
            "password-error" => implode("<br>", $passwordErrors)
        );
        $errorMessagesString = http_build_query($errorMessages);
        header("Location: reset_password.php?token=$token&" . $errorMessagesString);
        exit();
    }

    // Hash the new password before updating it in the database
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password and reset token in the database
    $updatePasswordQuery = "UPDATE users SET password_hash = ?, reset_token = NULL WHERE reset_token = ?";
    $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
    $updatePasswordStmt->bind_param("ss", $password_hash, $token);

    if ($updatePasswordStmt->execute()) {
        // Password reset successful, redirect to the login page or another page
        header("Location: login.php?reset=success");
        exit();
    } else {
        // Password reset failed
        header("Location: reset_password.php?token=$token&error=reset-failed");
        exit();
    }

    // Close database connection
    $updatePasswordStmt->close();
    $checkTokenStmt->close();
    $conn->close();
} else {
    // Redirect to the login page or another page
    header("Location: login.php");
    exit();
}
?>
