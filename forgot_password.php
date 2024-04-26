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

include 'views/pageBuilder.php';
include 'views/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
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

<!-- Start Account Sign In Area -->
<div class="account-login section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <form class="card login-form inner-content" action="process_forgot_password.php" method="post">
                    <div class="card-body">
                        <div class="title">
                            <h3>Forgot Password</h3>
                            <p>Enter your email to reset your password.</p>
                        </div>
                        <div class="input-head">
                            <div class="form-group input-group">
                                <label> <i class="lni lni-envelope"></i> </label>
                                <input class="form-control" type="email" name="email" id="email"
                                    placeholder="Enter your email" required />
                            </div>
                        </div>
                        <!-- Display the error message in red -->
                        <div class="email-error error-message"></div>
                        <div class="light-rounded-buttons">
                            <button class="btn primary-btn" type="submit">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</html>
<!-- End Account Sign In Area -->

<?php
include 'views/footer.php';
?>
