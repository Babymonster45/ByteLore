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
    <main><br>
        <h2>View Game List</h2><br>
        <div><a class="button" href="/game_list.php">Game List</a></div>
        <div><a class="button" href="/create_page">Create Page</a></div><br><br>
        <h2>Recently Created Pages</h2><br>
        <?php include('recent_pages.php'); ?>
    </main>
</body>
</html>
