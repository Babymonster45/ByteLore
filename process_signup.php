<?php
// Start a session to manage user login state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the signup form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Perform server-side validation, e.g., check if username or email is already in use

    // Establish a database connection
    $conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Hash the password before storing it in the database
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $query = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $username, $email, $password_hash);

    if ($stmt->execute()) {
        // Registration was successful
        $_SESSION["user_id"] = $stmt->insert_id; // Set a session variable to indicate the user is logged in
        header("Location: index.php"); // Redirect to the homepage or another page
    } else {
        // Registration failed
        header("Location: signup.php?error=1"); // Redirect back to the signup page with an error message
    }

    // Close database connection
    $stmt->close();
    $db->close();
}
?>
