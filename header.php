<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ByteLore</title>
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <header>
        <h1>Welcome to ByteLore</h1>
        
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="games_list.php">Game List</a></li>

            <?php
            // Check if the user is logged in and display appropriate buttons
            if (isset($_SESSION["user_id"])) {
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a href="signup.php">Sign Up</a></li>';
            }
            ?>
        </ul>
    </nav>
</body>
</html>
