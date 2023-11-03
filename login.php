<?php
// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

// Initialize an error message variable
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $username = $_POST["username"];
    $password = $_POST["password"];

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

            header("Location: /"); // Redirect to the homepage or another page
        } else {
            // Password is incorrect
            $error_message = "Incorrect username and/or password.";
        }
    } else {
        // User not found
        $error_message = "Incorrect username and/or password.";
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">   
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>
    <form action="process_login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        
        <!-- Display the error message in red text -->
        <?php if (!empty($error_message)) : ?>
            <div style="color: red;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <input class="button" type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>
</html>
