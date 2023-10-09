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
        <h1>Welcome to ByteLore Website</h1>
    </header>
    <main>
	<div><a class="button" href="/save_page.php">Create Page</a></div><br><br>

	<h2>Recently Created Pages</h2><br>
  	<?php include('recent_pages.php'); ?>
	</main>

</body>
</html>

