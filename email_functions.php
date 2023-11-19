<?php
// Include PHPMailer autoloader
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start a session to manage user login state
session_start();

// Function to send a verification email using PHPMailer
function sendVerificationEmail($to, $verificationLink) {
    $mail = new PHPMailer(true);

    try {
        //Server settings for Gmail SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'byteloreemail@gmail.com'; // Your Gmail address
        $mail->Password = 'your_gmail_password'; // Your Gmail password or App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom('byteloreemail@gmail.com', 'ByteLore Email'); // Set the sender address
        $mail->addAddress($to); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Account Verification'; // Email subject
        $mail->Body = "Thank you for registering! Please verify your email by clicking the link: <a href='$verificationLink'>Verify Email</a>"; // Email body message

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}