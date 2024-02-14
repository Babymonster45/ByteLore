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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <header>
        <h1>Forgot Password</h1>
    </header>

    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>

    <form action="process_forgot_password.php" method="post">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" required>
        <input type="submit" value="Reset Password">
    </form>
</body>

</html>