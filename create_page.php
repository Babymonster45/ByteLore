<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the maximum file size (in bytes)
$maxFileSize = 5 * 1024 * 1024; // 5MB (adjust to your desired limit)

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Check if a file was uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $uploadedFile = $_FILES["image"];

        // Check the file size
        if ($uploadedFile["size"] <= $maxFileSize) {
            // Define the target directory for file uploads
            $uploadDirectory = "uploads/"; // Adjust to your desired directory

            // Generate a unique filename to prevent overwriting
            $targetFileName = $uploadDirectory . uniqid() . "_" . $uploadedFile["name"];

            // Move the uploaded file to the target directory
            if (move_uploaded_file($uploadedFile["tmp_name"], $targetFileName)) {
                // File upload successful, you can save the file path in the database
                $filePath = $targetFileName;

                // Insert data into the database
                $sql = "INSERT INTO user_pages (title, content, image_path) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $title, $content, $filePath);

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
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "File size exceeds the maximum allowed size.";
        }
    } else {
        echo "Please select a file to upload.";
    }
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
        <label for="image">Upload an Image (Max 5MB):</label>
        <input type="file" id="image" name="image" accept="image/*">
        <input type="submit" value="Save Page">
    </form>
    <a href="/">Home Page</a>
</body>
</html>
