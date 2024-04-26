<?php
// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

include 'views/pageBuilder.php';
include 'views/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Username/Password Reset Email Sent</title>
    <link rel="stylesheet" href="login.css">
</head>

<!-- Start Account Sign In Area -->
<div class="account-login section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="card login-form inner-content">
                    <div class="card-body">
                        <div class="title">
                            <h3>Username/Password Reset Email Sent</h3>
                            <p>A Username/Password reset email has been sent to your provided email address.</p>
                            <p>Please check your inbox (and spam/junk folder) for the verification email.</p>
                            <p>Click the link in the email to verify your account.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</html>
<!-- End Account Sign In Area -->

<?php
include 'views/footer.php';
?>
