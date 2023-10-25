<?php
// Start a session to manage user login state
session_start();

// Log the user out by destroying the session
session_destroy();

// Redirect to the homepage or another page after logging out
header("Location: index.php");
?>
