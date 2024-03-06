<?php
// Start a session to manage user login state
session_start();

// Include necessary files and configurations
include('/secure_config/config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];

    // Check if the token exists in the database
    $checkTokenQuery = "SELECT id, reset_token_created_at, reset_token_expires_at FROM users WHERE reset_token = ?";
    $checkTokenStmt = $conn->prepare($checkTokenQuery);
    $checkTokenStmt->bind_param("s", $token);
    $checkTokenStmt->execute();
    $checkTokenStmt->store_result();

    if ($currentTime > $tokenExpiresAt) {
        // Token has expired, remove it from the database
        $removeTokenQuery = "UPDATE users SET reset_token = NULL, reset_token_created_at = NULL, reset_token_expires_at = NULL WHERE id = ?";
        $removeTokenStmt = $conn->prepare($removeTokenQuery);
        $removeTokenStmt->bind_param("i", $userData['id']);
        $removeTokenStmt->execute();

        echo "Reset token has expired.";
    }
        else {
        // Token is valid, display the password reset form
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

        <body>
            <header>
                <h1>Reset Password</h1>
            </header>
            <div class="subheader">
                <?php include('header.php'); ?>
            </div><br>
            <form action="process_reset_password.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="password">New Password:</label>
                <input type="password" name="password" id="password" required><br>
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required><br>
                <div class="password-error error-message"></div>

                <input class="button" type="submit" value="Reset Password">
            </form>
        </body>

        </html>
        <?php
        exit();
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