<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the page ID from the URL
if (isset($_GET['id'])) {
    $pageID = $_GET['id'];

    // Retrieve the page from the database using the ID
    $sql = "SELECT * FROM user_pages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pageID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $pageTitle = $row['title'];
        $pageContent = $row['content'];
        $pageCreatedAt = $row['created_at'];
    } else {
        echo "Page not found.";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid page ID.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="/home_page.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1><?php echo $pageTitle; ?></h1>
    <p><?php echo $pageContent; ?></p>
    <p>Created at: <?php echo $pageCreatedAt; ?></p>
    <a href="/index.php">Home Page</a>
</body>
</html>

