<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form and capitalize the title
    $title = ucwords($_POST["title"]);
    $content = $_POST["content"];

    // Check if a page with the same title already exists
    $checkSql = "SELECT COUNT(*) as count FROM user_pages WHERE title = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $title);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $count = $checkResult->fetch_assoc()['count'];

    if ($count > 0) {
        // A page with the same title already exists, show an error message
        echo "That game already exists. Please look in our games list.";
    } else {
        // Handle image upload
        $uploadDir = "/var/www/uploads/";
        $imagePath = $uploadDir . basename($_FILES["image"]["name"]);

        if (isset($_FILES["image"])) {
            if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                    // Insert data into the database
                    $sql = "INSERT INTO user_pages (title, content, image_path) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $title, $content, $imagePath);

                    if ($stmt->execute()) {
                        // Get the ID of the newly created page
                        $newPageID = $stmt->insert_id;

                        // Close the prepared statement
                        $stmt->close();

                        // Close the database connection
                        $conn->close();

                        // Redirect the user to their new page
                        header("Location: view_page.php?id=$newPageID");
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    // Close the prepared statement
                    $stmt->close();
                } else {
                    echo "Error moving uploaded image to the destination.";
                }
            } else {
                echo "File upload error: " . $_FILES["image"]["error"];
            }
        } else {
            echo "Image not uploaded.";
        }
    }

    // Close the prepared statement and database connection if an error occurred
    $checkStmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>
    <link rel="stylesheet" href="/create_page.css">
</head>
<body>
    <h1>Create a New Page</h1>
    <form action="create_page.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Megaman" required>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" cols="50" placeholder="Enter text here.." required></textarea>
        <label for="image">Upload an Image:</label>
        <input type="file" name="image" id="image" accept="image/*">
        <input type="submit" value="Create Page">
    </form>
    <a href="/">Home Page</a>
</body>
</html>
