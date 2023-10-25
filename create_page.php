<?php
// Includes the authentication script to check if the user is logged in
include('authentication.php');

// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $title = ucwords($_POST["title"]);
    $content = $_POST["content"];

    // Define the maximum file size (250KB)
    $maxFileSize = 250 * 1024; // 250KB

    if ($_FILES["image"]["size"] > $maxFileSize) {
        echo "File size exceeds the limit of 250KB.";
        exit();
    }

    // Remove spaces and special characters from the title
    $title = preg_replace('/[^A-Za-z0-9]/', '', $title);

    // Check if a page with the same title already exists
    $checkSql = "SELECT COUNT(*) as count FROM user_pages WHERE title = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $title);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $count = $checkResult->fetch_assoc()['count'];

    if ($count > 0) {
        echo "A page with the same title already exists. Please choose a different title.";
    } else {
        // Handle image upload
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = "/var/www/uploads/";
            $newFileName = $title . "_" . time() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $imagePath = $uploadDir . $newFileName;
            $urlImagePath = "/uploads/" . $newFileName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                // Insert data into the database
                $sql = "INSERT INTO user_pages (title, content, image_path) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $title, $content, $urlImagePath);

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
                echo "Error moving the uploaded image to the destination.";
            }
        } else {
            echo "Please upload an image of the game.";
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
    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>
    <form action="create_page.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Megaman" required>

        <label for="image">Upload an Image (Max: 250KB):</label>
        <p id="file-upload-text" class="file-upload-text">Choose an Image</p>
        <label for="image" class="custom-file-label">Choose an Image</label>
        <input type="file" name="image" id="image" accept="image/*" class="custom-file-input">

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" cols="50" placeholder="Enter text here.." required></textarea>
        <input type="submit" value="Create Page">
    </form>
    <a href="/">Home Page</a>
</body>
</html>
