<?php
// Start a session to manage user login state
session_start();

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
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>Sign Up</h1>
    </header>
    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>
    <form action="process_signup.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <?php
        // Check for notifications and display them to the user
        if (isset($_SESSION["notification"])) {
           echo "<div class='notification'>" . $_SESSION["notification"] . "</div>";
           unset($_SESSION["notification"]); // Clear the notification after displaying it
        }
        ?>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <input class="button" type="submit" value="Sign Up">
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
