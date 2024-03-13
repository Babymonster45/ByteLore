<?php
// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your database connection script
include('/secure_config/config.php');

if (isset($_SESSION["user_id"]) && isset($_COOKIE["remember_me_token"])) {
    // User is logged in and a "Remember Me" cookie exists

    // Retrieve the token from the cookie
    $token = $_COOKIE["remember_me_token"];

    // Delete the token from the database
    $stmt = $conn->prepare("DELETE FROM remember_me_tokens WHERE user_id = ? AND token = ?");
    $stmt->bind_param("is", $_SESSION["user_id"], $token);
    $stmt->execute();

    // Delete the cookie
    setcookie("remember_me_token", "", time() - 3600, "/", "", true, true);
}

// Log the user out by destroying the session
session_destroy();

// Redirect to the homepage or another page after logging out
header("Location: /");
?>
