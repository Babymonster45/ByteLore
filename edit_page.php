<?php
include ('remember_me.php');

include ('not_logged_in_check.php');

include ('/secure_config/config.php');

$current_user_id = $_SESSION['user_id'];

// Fetch the role of the current user from the database
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
} else {
    echo "User not found.";
    exit();
}

$imageUploaded = false;

if (isset ($_GET['id'])) {
    $pageID = $_GET['id'];

    // Check if the current user is allowed to edit the page
    $sql = "SELECT * FROM user_pages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pageID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($role >= 1 || $row['created_by'] == $current_user_id) {
            // The user is an admin/editor or the creator of the page
            $title = $row['title'];
            $content = $row['content'];
            $imagePath = $row['image_path'];
        } else {
            echo "You do not have permission to edit this page.";
            exit();
        }
    } else {
        echo "Page not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newTitle = ucwords($_POST["title"]);
    $newContent = $_POST["content"];
    $pageID = $_POST["page_id"]; // Retrieve the page ID from the form data

    // Fetch the old image path from the database
    $sql = "SELECT image_path FROM user_pages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pageID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $imagePath = $row['image_path'];
    } else {
        echo "Page not found or you do not have permission to edit this page.";
        exit();
    }

    if (!preg_match('/^[\x20-\x7E]+$/', $newTitle)) {
        echo "Title contains invalid characters. Please use only ASCII characters in the range 32-126.";
        exit();
    }

    $maxFileSize = 250 * 1024;

    if ($_FILES["image"]["size"] > $maxFileSize) {
        echo "File size exceeds the limit of 250KB.";
        exit();
    }

    $checkSql = "SELECT COUNT(*) as count FROM user_pages WHERE title = ? AND id != ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $newTitle, $pageID);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $count = $checkResult->fetch_assoc()['count'];

    if ($count > 0) {
        echo "A page with the same title already exists. Please choose a different title.";
    } else {
        // Check if a new image is uploaded
        if (isset ($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK && $_FILES["image"]["size"] > 0) {
            // A new image is uploaded
            $oldImagePath = "/var/www" . $imagePath; // Prepend the path to the web root directory
            echo "Old image path: " . $oldImagePath; // Add this line to debug the image path
            if (file_exists($oldImagePath)) {
                if (!unlink($oldImagePath)) {
                    $error = error_get_last();
                    echo "Error deleting the old image: " . $error['message'];
                    exit();
                }
            } else {
                echo "The old image file does not exist at the specified path: " . $oldImagePath;
                exit();
            }

            $uploadDir = "/var/www/uploads/";
            $newFileName = $newTitle . "_" . time() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $newImagePath = $uploadDir . $newFileName;
            $urlImagePath = "/uploads/" . $newFileName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $newImagePath)) {
                $imageUploaded = true;
            } else {
                echo "Error moving the uploaded image to the destination.";
                exit();
            }
        }


        if ($imageUploaded) {
            // If a new image is uploaded, update the image path
            $sql = "UPDATE user_pages SET title=?, content=?, image_path=? WHERE id=? AND created_by=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $newTitle, $newContent, $urlImagePath, $pageID, $current_user_id);
        } else {
            // If no new image is uploaded, do not update the image path
            $sql = "UPDATE user_pages SET title=?, content=? WHERE id=? AND created_by=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $newTitle, $newContent, $pageID, $current_user_id);
        }

        if ($stmt->execute()) {
            header("Location: view_page.php?id=$pageID");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        if ($imageUploaded || $newTitle != $title || $newContent != $content) {
            $sql = "UPDATE user_pages SET title=?, content=?, image_path=? WHERE id=? AND created_by=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $newTitle, $newContent, $urlImagePath, $pageID, $current_user_id);

            if ($stmt->execute()) {
                header("Location: view_page.php?id=$pageID");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "No changes were made.";
        }
    }
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
                const fileSize = event.target.files[0].size / 1024 / 1024;
                const maxSize = 0.25;

                if (fileSize > maxSize) {
                    errorText.textContent = 'File size exceeds the limit of 250KB.';
                    fileInput.value = '';
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
        <?php include ('header.php'); ?>
    </div><br>
    <form action="edit_page.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="page_id" value="<?php echo $pageID; ?>">

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>

        <label for="image">Current Image:</label><br>
        <img src="<?php echo $imagePath; ?>" alt="Current Image"><br>

        <label for="image">Upload a New Image (Max: 250KB):</label>
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