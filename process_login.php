<?php
// Start a session to manage user login state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform server-side validation if needed

    // Establish a database connection
    $conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Query the database to find the user
    $query = "SELECT id, username, password_hash FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password_hash);
    $stmt->fetch();

    // Verify the user's password
    if (password_verify($password, $db_password_hash)) {
        // Password is correct
        $_SESSION["user_id"] = $user_id; // Set a session variable to indicate the user is logged in
        header("Location: index.php"); // Redirect to the homepage or another page
    } else {
        // Password is incorrect
        header("Location: login.php?error=1"); // Redirect back to the login page with an error message
    }

    // Close database connection
    $stmt->close();
    $db->close();
}
?>
