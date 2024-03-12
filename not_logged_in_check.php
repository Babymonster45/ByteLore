<?php
// Start a session to manage user login state
session_start();

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

        // Delete the used token from the database
        $stmt = $conn->prepare("DELETE FROM remember_me_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Generate a new token and store it in the database and in a cookie
        // ... similar to the login script ...
    } else {
        // Token is invalid or has expired

        // Delete the cookie
        setcookie("remember_me_token", "", time() - 3600, "/", "", true, true);
    }
}
?>
