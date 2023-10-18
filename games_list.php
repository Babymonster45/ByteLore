<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve page titles from the database in alphabetical order
$sql = "SELECT title, id FROM user_pages ORDER BY title ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pages = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $pages = array();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Games List</title>
    <link rel="stylesheet" href="/home_page.css">
</head>
<body>
    <header>
        <h1>Games List</h1>
    </header>
    <div class="games-list">
        <ul>
            <?php foreach ($pages as $page): ?>
                <li><a class="button" href="view_page.php?id=<?php echo $page['id']; ?>"><?php echo $page['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
