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
    <title>Forgot Username</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error-message {
            color: red;
            margin-top: 5px;
        } 
    </style>
    <script>
        // JavaScript to display error messages in red
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const emailError = urlParams.get("email-error");

            if (emailError) {
                const emailErrorDiv = document.querySelector(".email-error");
                emailErrorDiv.innerHTML = emailError;
            }
        });
    </script>
</head>

<body>
    <header>
        <h1>Forgot Username</h1>
    </header>

    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>

    <form action="process_forgot_username.php" method="post">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" required>
        <input type="submit" value="Reset Username">
        <div class="email-error error-message"></div>
    </form>
</body>

</html>