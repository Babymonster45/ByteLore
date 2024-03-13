<?php
// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include your database connection script
include('/secure_config/config.php');

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_me_token"])) {
    // User is not logged in and a "Remember Me" cookie exists

    // Retrieve the token from the cookie
    $token = $_COOKIE["remember_me_token"];

    // Look up the token in the database
    $stmt = $conn->prepare("SELECT user_id, expires FROM remember_me_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($user_id, $expires);

    if ($stmt->fetch() && new DateTime() < new DateTime($expires)) {
        // Token is valid and has not expired

        // Log the user in
        $_SESSION["user_id"] = $user_id;
    } else {
        // Token is invalid or has expired

        // Delete the cookie
        setcookie("remember_me_token", "", time() - 3600, "/", "", true, true);
    }
}
?>
