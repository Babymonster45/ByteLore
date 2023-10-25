<?php
// Start a session to manage user login state
session_start();
?>

<?php include('header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ByteLore</title>
    <link rel="stylesheet" href="/home_page.css">
    <link rel="icon" href="/favicon.ico">
</head>
<body>
    <header>
        <h1>Welcome to ByteLore</h1>
    </header>
    <main>
        <h2>View Games List</h2>
        <div><a class="button" href="/games_list.php">Game List</a></div>
    </main>
    <br>
    <main>
        <h2>Recently Created Pages</h2>
        <?php include('recent_pages.php'); ?>
    </main>
    <br>
    <main>
        <h2>Create a Page</h2>
        <?php
        // Check if the user is logged in
        if (isset($_SESSION["user_id"])) {
            echo '<div><a class="button" href="/create_page.php">Create Page</a></div>';
        } else {
            echo '<p>You must be logged in to create a page. Please <a href="login.php">log in</a>.</p>';
        }
        ?>
    </main>
</body>
</html>
