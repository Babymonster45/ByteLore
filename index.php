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
        <h2>View Games List</h2>
        <div><a class="button" href="/games_list.php">Game List</a></div>
    </main><br><main>
        <h2>Recently Created Pages</h2>
        <?php include('recent_pages.php'); ?>
    </main><br><main>
        <h2>Create a Page</h2>
        <div><a class="button" href="/create_page">Create Page</a></div>
    </main>
</body>
</html>
