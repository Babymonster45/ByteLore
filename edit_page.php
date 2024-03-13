<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Includes the authentication script to make sure the user is not logged in
include('not_logged_in_check.php');

// Establish a database connection
include('/secure_config/config.php');

// Assuming you have a way to get the current user's id
$current_user_id = $_SESSION['user_id']; // replace this with your actual code to get the current user's id

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$imageUploaded = false; // Variable to track whether the image has been successfully uploaded

// Retrieve the page ID from the URL
if (isset($_GET['id'])) {
    $pageID = $_GET['id'];

    // Retrieve the page from the database using the ID
    $sql = "SELECT * FROM user_pages WHERE id = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $pageID, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Pre-fill the form with the current page data
        $title = $row['title'];
        $content = $row['content'];
        $imagePath = $row['image_path'];
    } else {
        echo "Page not found or you do not have permission to edit this page.";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $title = ucwords($_POST["title"]);
    $content = $_POST["content"];

    // Checks if the title contains only ASCII characters in the range 32-126
    if (!preg_match('/^[\x20-\x7E]+$/', $title)) {
        echo "Title contains invalid characters. Please use only ASCII characters in the range 32-126.";
        exit();
    }

    // Define the maximum file size (250KB)
    $maxFileSize = 250 * 1024; // 250KB

    if ($_FILES["image"]["size"] > $maxFileSize) {
        echo "File size exceeds the limit of 250KB.";
        exit();
    }

    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "/var/www/uploads/";
        $newFileName = $title . "_" . time() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $imagePath = $uploadDir . $newFileName;
        $urlImagePath = "/uploads/" . $newFileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            $imageUploaded = true;
        } else {
            echo "Error moving the uploaded image to the destination.";
        }
    } else {
        echo "Please upload an image of the game.";
    }

    if ($imageUploaded) {
        // Update data in the database
        $sql = "UPDATE user_pages SET title=?, content=?, image_path=? WHERE id=? AND created_by=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $content, $urlImagePath, $pageID, $current_user_id);

        if ($stmt->execute()) {
            // Redirect the user to their updated page
            header("Location: view_page.php?id=$pageID");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the prepared statement and database connection if an error occurred
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <link rel="stylesheet" href="/create_page.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('image');
            const fileText = document.getElementById('file-upload-text');
            const errorText = document.getElementById('error');

            fileInput.addEventListener('change', (event) => {
                const filename = event.target.files[0].name;
                const fileSize = event.target.files[0].size / 1024 / 1024; // in MB
                const maxSize = 0.25; // 250KB in MB

                if (fileSize > maxSize) {
                    errorText.textContent = 'File size exceeds the limit of 250KB.';
                    fileInput.value = ''; // clear the input
                } else {
                    errorText.textContent = '';
                    fileText.textContent = filename;
                }
            });
        });
    </script>
</head>

<body>
    <h1>Edit Page</h1>
    <div class="subheader">
        <?php include('header.php'); ?>
    </div><br>
    <form action="edit_page.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="page_id" value="<?php echo $pageID; ?>">

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>

        <label for="image">Upload an Image (Max: 250KB):</label>
        <p id="file-upload-text" class="file-upload-text" placeholder="Choose an Image">Choose an Image</p>
        <p id="error" style="color: red;"></p>
        <label for="image" class="custom-file-label">Choose an Image</label>
        <input type="file" name="image" id="image" accept="image/*" class="custom-file-input">

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" cols="50" required><?php echo $content; ?></textarea>
        <input type="submit" value="Update Page">
    </form>
</body>

</html>
