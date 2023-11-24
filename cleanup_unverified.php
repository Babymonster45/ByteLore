<?php
// Includes the authentication script to make sure the user is not logged in
include('not_logged_in_check.php');

// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

// Establish a database connection
include('/secure_config/config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Calculate the timestamp 24 hours ago
$twentyFourHoursAgo = time() - (24 * 60 * 60);

// Prepare and execute the SQL query to delete records older than 24 hours
$deleteQuery = "DELETE FROM unverified WHERE timestamp_column < ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("i", $twentyFourHoursAgo);
$deleteStmt->execute();

$deleteStmt->close();
$conn->close();
?>
