<?php
// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the forgot password form
    $email = $_POST["email"];

    // Initialize separate error arrays for Username, Email, and Password
    $emailErrors = array();

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $emailErrors[] = "Not a valid email address.";
    }

    // Establish a database connection
    include('/secure_config/config.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email is associated with a user
    $checkEmailQuery = "SELECT id, username FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        // User found, generate a unique reset token
        $resetToken = bin2hex(random_bytes(32)); // Token length

        // Store the reset token in the database
        $userId = $checkEmailStmt->fetch_assoc()['id']; // Use fetch_assoc() instead of fetch()
        $storeTokenQuery = "UPDATE users SET reset_token = ? WHERE id = ?";
        $storeTokenStmt = $conn->prepare($storeTokenQuery);
        $storeTokenStmt->bind_param("si", $resetToken, $userId);
        $storeTokenStmt->execute();

        // Send an email with the reset link
        $resetLink = "https://bytelore.cheeseindustries.de/reset_password.php?token=$resetToken";
        $verification_message = "Here is your password reset link! Please click the following link to verify your account: <a href='$resetLink'>$resetLink</a>";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'byteloreemail@gmail.com'; // Gmail
            $mail->Password = getenv('EMAIL_PASSWORD'); // Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('byteloreemail@gmail.com', 'Bytelore');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Account Verification';
            $mail->Body = $verification_message;

            $mail->send();
            // Redirect to reset message page
            header("Location: reset_sent.php");
            exit();
        } catch (Exception $e) {
            // Registration failed
            $error_message = "Failed to send email: " . $insertUnverifiedStmt->error;
            header("Location: signup.php?error=" . urlencode($error_message));
        }

    } else {
        // User not found
        $emailErrors[] = "User not found.";
    }

    // If there are errors, redirect back to signup.php with the error messages
    if (!empty($errorMessages) || !empty($usernameErrors) || !empty($emailErrors) || !empty($passwordErrors)) {
        $errorMessages = array(
            "email-error" => implode("<br>", $emailErrors)
        );
        $errorMessagesString = http_build_query($errorMessages);
        header("Location: signup.php?" . $errorMessagesString);
        exit();
    }

    // Close database connections
    $checkEmailStmt->close();
    $storeTokenStmt->close();
    $conn->close();
}
?>