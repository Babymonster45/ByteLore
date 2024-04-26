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
        // Token is valid, display the username reset form
        include 'views/pageBuilder.php';
        include 'views/header.php';
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Reset Username</title>
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
                    const usernameError = urlParams.get("username-error");

                    if (usernameError) {
                        const usernameErrorDiv = document.querySelector(".username-error");
                        usernameErrorDiv.innerHTML = decodeURIComponent(usernameError);
                    }
                });
            </script>
        </head>

        <!-- Start Account Sign In Area -->
        <div class="account-login section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8">
                        <form class="card login-form inner-content" action="process_reset_username.php" method="post">
                            <div class="card-body">
                                <div class="title">
                                    <h3>Reset Username</h3>
                                    <p>Enter your new username and confirm it.</p>
                                </div>
                                <div class="input-head">
                                    <div class="form-group input-group">
                                        <label> <i class="lni lni-user"></i> </label>
                                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                                        <input class="form-control" type="text" name="username" id="username"
                                            placeholder="New Username" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <label> <i class="lni lni-user"></i> </label>
                                        <input class="form-control" type="text" name="confirm_username" id="confirm_username"
                                            placeholder="Confirm New Username" required>
                                    </div>
                                </div>
                                <!-- Display the error message in red -->
                                <div class="username-error error-message"></div>
                                <div class="light-rounded-buttons">
                                    <button class="btn primary-btn" type="submit">Reset Username</button>
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