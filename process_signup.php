<?php
// Start a session to manage user login state
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the signup form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Initialize separate error arrays for Username, Email, and Password
    $usernameErrors = array();
    $emailErrors = array();
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

    // Check if the username is already in use
    $checkUsernameQuery = "SELECT id FROM users WHERE username = ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
    $checkUsernameStmt->bind_param("s", $username);
    $checkUsernameStmt->execute();
    $checkUsernameStmt->store_result();

    if ($checkUsernameStmt->num_rows > 0) {
        $usernameErrors[] = "Username is already in use.";
    }

    // Check if the email is already in use
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $emailErrors[] = "Email is already in use.";
    }

    // If there are errors, redirect back to signup.php with the error messages
    if (!empty($errorMessages) || !empty($usernameErrors) || !empty($emailErrors) || !empty($passwordErrors)) {
        $errorMessages = array(
            "username-error" => implode("<br>", $usernameErrors),
            "email-error" => implode("<br>", $emailErrors),
            "password-error" => implode("<br>", $passwordErrors),
        );
        $errorMessagesString = http_build_query($errorMessages);
        header("Location: signup.php?" . $errorMessagesString);
        exit();
    }

    // Generate a unique verification token
    $verification_token = bin2hex(random_bytes(32));

    // Insert data into the unverified table
    $insertUnverifiedQuery = "INSERT INTO unverified (email, verification_token) VALUES (?, ?)";
    $insertUnverifiedStmt = $conn->prepare($insertUnverifiedQuery);
    $insertUnverifiedStmt->bind_param("ss", $email, $verification_token);

    if ($insertUnverifiedStmt->execute()) {
        // Send verification email
        $verification_link = 'https://bytelore.cheeseindustries.de/verify.php?email=' . urlencode($email);
            $verification_message = "Thank you for registering! Please click the following link to verify your account: <a href='$verification_link'>Verify Account</a>";
    
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
                // Redirect to verification message page
                header("Location: verification_sent.php");
                exit();
            } catch (Exception $e) {
                // Registration failed
                $error_message = "Failed to send email: " . $insertUnverifiedStmt->error;
                header("Location: signup.php?error=" . urlencode($error_message));
            }
        } else {
            // Registration failed
            $error_message = "Registration failed: " . $insertUnverifiedStmt->error;
            header("Location: signup.php?error=" . urlencode($error_message));
        }
    
        // Close database connections
        $insertUnverifiedStmt->close();
        $checkUsernameStmt->close();
        $checkEmailStmt->close();
        $conn->close();
    }
    ?>