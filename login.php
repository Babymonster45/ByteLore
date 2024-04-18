<?php
// Checks if the user tagged remember me
include ('remember_me.php');

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
    <title>Sign In</title>
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

<!-- Start Account Sign In Area -->
<div class="account-login section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <form class="card login-form inner-content" action="process_login.php" method="post">
                    <div class="card-body">
                        <div class="title">
                            <h3>Sign In Now</h3>
                            <p>Sign in by entering the information below.</p>
                        </div>
                        <div class="input-head">
                            <div class="form-group input-group">
                                <label> <i class="lni lni-user"></i> </label>
                                <input class="form-control" type="text" name="username" id="username"
                                    placeholder="Enter your user name" required />
                            </div>
                            <div class="form-group input-group">
                                <label> <i class="lni lni-lock-alt"></i> </label>
                                <input class="form-control" type="password" name="password" id="password"
                                    placeholder="Enter your password" required />
                            </div>
                        </div>
                        <!-- Display the error message in red -->
                        <div class="error-message"></div>
                        <div class="d-flex flex-wrap justify-content-between bottom-content">
                            <div class="form-check">
                                <input type="checkbox" name="remember_me" id="remember_me"
                                    class="form-check-input width-auto" />
                                <label class="form-check-label" for="remember_me">Remember me</label>
                            </div>
                            <a class="lost-user" href="forgot_username.php">
                                Forgot username?
                            </a>
                            <a class="lost-pass" href="forgot_password.php">
                                Forgot password?
                            </a>
                        </div>
                        <div class="light-rounded-buttons">
                            <button class="btn primary-btn" type="submit">Sign In</button>
                            <a href="signup.php" class="btn primary-btn-outline">
                                Create Account
                            </a>
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