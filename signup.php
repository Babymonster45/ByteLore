<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
    <script>
    // JavaScript to display error messages under the corresponding text boxes
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const errorMessage = urlParams.get("messages");

        if (errorMessage) {
            const errorDiv = document.querySelector(".error-message");
            errorDiv.innerHTML = errorMessage;
            errorDiv.style.color = "red";
        }
    });
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
        <div class="username-error"></div>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <div class="email-error"></div>
        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>
        <div class="password-error"></div>
        <br>

        <input class="button" type="submit" value="Sign Up">
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
