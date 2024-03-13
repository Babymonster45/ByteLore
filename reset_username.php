<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files and configurations
include('/secure_config/config.php');

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

        <body>
            <header>
                <h1>Reset Username</h1>
            </header>
            <div class="subheader">
                <?php include('header.php'); ?>
            </div><br>
            <form action="process_reset_username.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="username">New Username:</label>
                <input type="text" name="username" id="username" required><br>
                <label for="confirm_username">Confirm New Username:</label>
                <input type="username" name="confirm_username" id="confirm_username" required><br>
                <div class="username-error error-message"></div>

                <input class="button" type="submit" value="Reset Username">
            </form>
        </body>

        </html>
        <?php
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
