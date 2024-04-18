<?php
// Checks if the user tagged remember me
include ('remember_me.php');

// Includes the authentication script to make sure the user is not logged in
include ('not_logged_in_check.php');

// Establish a database connection
include ('/secure_config/config.php');

// Assuming you have a way to get the current user's id
$current_user_id = $_SESSION['user_id']; // replace this with your actual code to get the current user's id

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$imageUploaded = false; // Variable to track whether the image has been successfully uploaded

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
            // Remove all non-alphabet characters from the title
            $filetitle = preg_replace('/[^a-zA-Z]/', '', $title);
            $newFileName = $filetitle . "_" . time() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
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
            // Insert data into the database
            $sql = "INSERT INTO user_pages (title, content, image_path, created_by) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $content, $urlImagePath, $current_user_id);

            if ($stmt->execute()) {
                // Get the ID of the newly created page
                $newPageID = $stmt->insert_id;

                // Redirect the user to their new page
                header("Location: view_page.php?id=$newPageID");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    // Close the prepared statement and database connection if an error occurred
    $checkStmt->close();
    $conn->close();
}
?>
<?php
include ('remember_me.php');
include ('not_logged_in_check.php');
include ('/secure_config/config.php');

$current_user_id = $_SESSION['user_id'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$imageUploaded = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = ucwords($_POST["title"]);
    $content = $_POST["content"];

    if (!preg_match('/^[\x20-\x7E]+$/', $title)) {
        echo "Title contains invalid characters. Please use only ASCII characters in the range 32-126.";
        exit();
    }

    $maxFileSize = 250 * 1024; // 250KB

    if ($_FILES["image"]["size"] > $maxFileSize) {
        echo "File size exceeds the limit of 250KB.";
        exit();
    }

    $checkSql = "SELECT COUNT(*) as count FROM user_pages WHERE title = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $title);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $count = $checkResult->fetch_assoc()['count'];

    if ($count > 0) {
        echo "A page with the same title already exists. Please choose a different title.";
    } else {
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = "/var/www/uploads/";
            $filetitle = preg_replace('/[^a-zA-Z]/', '', $title);
            $newFileName = $filetitle . "_" . time() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
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
            $sql = "INSERT INTO user_pages (title, content, image_path, created_by) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $content, $urlImagePath, $current_user_id);

            if ($stmt->execute()) {
                $newPageID = $stmt->insert_id;
                header("Location: view_page.php?id=$newPageID");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
    $checkStmt->close();
    $conn->close();
}

include 'views/pageBuilder.php';
include 'views/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>
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
    <section class="call-action-area call-action-four">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="call-action-content text-center">
                        <h2 class="action-title">Create a New Page</h2>
                    </div>
                </div>
            </div>
        </div>
    </section><br>
    <form action="create_page.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Megaman" required>

        <label for="image">Upload an Image (Max: 250KB):</label>
        <p id="file-upload-text" class="file-upload-text" placeholder="Choose an Image">Choose
            an Image</p>
        <p id="error" style="color: red;"></p>
        <label for="image" class="btn primary-btn-outline">Choose an Image</label>
        <input type="file" name="image" id="image" accept="image/*" class="custom-file-input">

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" cols="50" placeholder="Enter text here.." required></textarea>
        <input class="btn primary-btn-outline" type="submit" value="Create Page">
    </form>
</body>

</html>

<?php include_once 'views/footer.php'; ?>