<?php
// Includes the authentication script to make sure the user is logged in
include('not_logged_in_check.php');

// Start a session to manage user login state
session_start();

// Log the user out by destroying the session
session_destroy();

// Redirect to the homepage or another page after logging out
header("Location: /");
?>
