<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>Sign Up</h1>
    </header>
    <div class="subheader">
        <?php include('header.php'); ?>
    </div>
    <form action="process_signup.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="Sign Up">
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
