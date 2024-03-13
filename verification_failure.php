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
    <title>Verification Failed</title>
    <link rel="stylesheet" href="verification.css">
</head>

<body><br>
    <header>
        <h1>Verification Failed</h1>
    </header>
    <div>
        <?php include('header.php'); ?>
    </div><br>
    <main>
        <form>
            <h4>
                <p>We're sorry, but the verification process failed.</p>
                <p>This could be due to an invalid or expired verification link.</p>
            </h4>
        </form>
    </main>
</body>

</html>