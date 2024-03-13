<?php
// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the home page
    header("Location: /");
    exit();
}
?>
