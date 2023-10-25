<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$pageTitle = $pageContent = $imagePath = $pageCreatedAt = '';

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
        $pageContent = nl2br($row['content']); // Convert newline characters to HTML line breaks
        $imagePath = $row['image_path']; // Retrieve the image path from the database
        $pageCreatedAt = $row['created_at'];
    } else {
        echo "Page not found.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Debugging: Output variables to help identify issues
echo "Page Title: " . $pageTitle . "<br>";
echo "Image Path: " . $imagePath . "<br>";
echo "Content: " . $pageContent . "<br>";
echo "Created At: " . $pageCreatedAt . "<br>";

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="/home_page.css">
    <style>
    body {
        background-color: #67b3b5;
    }
    </style>
</head>
<body>
    <main>
        <h1><?php echo $pageTitle; ?></h1>

        <?php if (!empty($imagePath) && file_exists($imagePath)) : ?>
            <img src="<?php echo $imagePath; ?>" alt="Uploaded Image">
        <?php else : ?>
            <p>Image not found or does not exist.</p>
        <?php endif; ?>

        <p><?php echo $pageContent; ?></p>
        <a href="/">Home Page</a>
        <p>Created at: <?php echo $pageCreatedAt; ?></p>
    </main>
</body>
</html>
