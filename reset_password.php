<?php
// Checks if the user tagged remember me
include ('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files and configurations
include ('/secure_config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];

    // Check if the token exists in the database
    $checkTokenQuery = "SELECT id FROM users WHERE reset_token = ?";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("s", $token);
    $checkTokenStmt->execute();
    $checkTokenStmt->store_result();

    if ($checkTokenStmt->num_rows > 0) {
        // Token is valid, display the password reset form
        include 'views/pageBuilder.php';
        include 'views/header.php';
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Reset Password</title>
            <link rel="stylesheet" href="login.css">
            <style>
                .error-message {
                    color: red;
                    margin-top: 5px;
                }
            </style>
            <script>
                // JavaScript to display error messages under the corresponding text boxes
                document.addEventListener("DOMContentLoaded", function () {
                    const urlParams = new URLSearchParams(window.location.search);
                    const passwordError = urlParams.get("password-error");

                    if (passwordError) {
                        const passwordErrorDiv = document.querySelector(".password-error");
                        passwordErrorDiv.innerHTML = decodeURIComponent(passwordError);
                    }
                });
            </script>
        </head>

        <!-- Start Account Sign In Area -->
        <div class="account-login section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8">
                        <form class="card login-form inner-content" action="process_reset_password.php" method="post">
                            <div class="card-body">
                                <div class="title">
                                    <h3>Reset Password</h3>
                                    <p>Enter your new password and confirm it.</p>
                                </div>
                                <div class="input-head">
                                    <div class="form-group input-group">
                                        <label> <i class="lni lni-lock-alt"></i> </label>
                                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                        <input class="form-control" type="password" name="password" id="password"
                                            placeholder="New Password" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <label> <i class="lni lni-lock-alt"></i> </label>
                                        <input class="form-control" type="password" name="confirm_password"
                                            id="confirm_password" placeholder="Confirm New Password" required>
                                    </div>
                                </div>
                                <!-- Display the error message in red -->
                                <div class="password-error error-message"></div>
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
        exit();
    } else {
        // Token is invalid or expired
        echo "Invalid or expired reset token.";
    }

    // Close database connection
    $checkTokenStmt->close();
    $conn->close();
} else {
    // Redirect to the login page or another page
    header("Location: login.php");
    exit();
}
?>