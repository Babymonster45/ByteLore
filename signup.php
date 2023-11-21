<?php
// Start a session to manage user login state
session_start();

// Check if the user is already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: /");
    exit();
}

// Define variables to store user input
$username = $email = $password = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the signup form
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    // Don't store the password for security reasons; only store it in the form if needed
    // $password = isset($_POST["password"]) ? $_POST["password"] : "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
            const emailError = urlParams.get("email-error");
            const passwordError = urlParams.get("password-error");

            if (usernameError) {
                const usernameErrorDiv = document.querySelector(".username-error");
                usernameErrorDiv.innerHTML = usernameError;
            }

            if (emailError) {
                const emailErrorDiv = document.querySelector(".email-error");
                emailErrorDiv.innerHTML = emailError;
            }

            if (passwordError) {
                const passwordErrorDiv = document.querySelector(".password-error");
                passwordErrorDiv.innerHTML = passwordError;
            }
        });

        // JavaScript to retain user input in form fields after form submission
        window.onload = function () {
            document.getElementById("username").value = "<?php echo htmlspecialchars($username); ?>";
            document.getElementById("email").value = "<?php echo htmlspecialchars($email); ?>";
        };
    </script>
</head>

<body>
    <header>
        <h1>Sign Up</h1>
    </header>
    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>
    <form action="process_signup.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>
        <div class="username-error error-message"></div>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <div class="email-error error-message"></div>
        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <div class="password-error error-message"></div>
        <br>

        <input class="button" type="submit" value="Sign Up">
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>

</html>