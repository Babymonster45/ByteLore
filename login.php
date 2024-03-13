<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script>
        // JavaScript to display error messages in red under the password box
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const errorMessage = urlParams.get("error");
            const errorDiv = document.querySelector(".error-message");

            if (errorMessage) {
                errorDiv.innerHTML = errorMessage;
                errorDiv.style.color = "red";
            }
        });
    </script>
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

        <!-- Remember me checkbox -->
        <label for="remember_me">Remember Me:</label>
        <input type="checkbox" name="remember_me" id="remember_me"> (30 days)</input>

        <!-- Display the error message in red -->
        <div class="error-message"></div>

        <input class="button" type="submit" value="Login">
    </form>
    <p>Forgot Username? <a href="forgot_username.php">Reset it here</a></p>
    <p>Forgot Password? <a href="forgot_password.php">Reset it here</a></p>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>

</html>