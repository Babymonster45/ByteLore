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
    <title>Verification Email Sent</title>
    <link rel="stylesheet" href="verification.css">   
</head>
<body><br>
    <header>
        <h1>Verification Email Sent</h1>
    </header>
    <main>
    <form>
        <?php include('header.php'); ?>
    </form><br>
        <form><h4>
         <p>A verification email has been sent to your provided email address.</p>
         <p>Please check your inbox (and spam/junk folder) for the verification email.</p>
         <p>Click the link in the email to verify your account.</p>
         </h4>
        </form>
    </main>
</body>
</html>
