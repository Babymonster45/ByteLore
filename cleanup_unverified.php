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

// Define the time interval for which records should be kept (30 minutes)
$timeInterval = strtotime('-30 minutes');

// Prepare and execute the SQL query to delete records older than the defined interval
$deleteQuery = "DELETE FROM unverified WHERE timestamp_column < ?";
$deleteStmt = $conn->prepare($deleteQuery);
$deleteStmt->bind_param("s", date('Y-m-d H:i:s', $timeInterval));
$deleteStmt->execute();

$deleteStmt->close();
$conn->close();
?>
