<?php
// Start a session to manage user login state
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]);

    // Establish a database connection
    include('/secure_config/config.php');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the database to find the user
    $query = "SELECT id, username, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_username, $db_password_hash);

    // Check if a user was found
    if ($stmt->fetch()) {
        // Verify the user's password
        if (password_verify($password, $db_password_hash)) {
            // Password is correct
            $_SESSION["user_id"] = $user_id; // Set a session variable to indicate the user is logged in

            // If "Remember Me" is checked, create a cookie to remember the user's login
            if ($remember_me) {
                $cookie_name = "remember_me_cookie";
                $cookie_value = base64_encode($username . "|" . $db_password_hash);
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 30 days
            }

            header("Location: /"); // Redirect to the homepage or another page
        } else {
            // Password is incorrect
            header("Location: login.php?error=1"); // Redirect back to the login page with an error message
        }
    } else {
        // User not found
        header("Location: login.php?error=2"); // Redirect back to the login page with an error message
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>
