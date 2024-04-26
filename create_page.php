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
    $description = $_POST["description"];
    $gameplay = $_POST["gameplay"];
    $history = $_POST["history"];
    $genre = $_POST["genre"];

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
            $sql = "INSERT INTO user_pages (title, content, description, gameplay, history, genre, image_path, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $title, $content, $description, $gameplay, $history, $genre, $urlImagePath, $current_user_id);

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
    <title>Add Game</title>
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
                        <h2 class="action-title">Add a New Game</h2>
                    </div>
                </div>
            </div>
        </div>
    </section><br>
    <form action="create_page.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Megaman" required>

        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" placeholder="Enter genre here.." required>

        <label for="description">Publisher/Studio:</label>
        <textarea id="description" name="description" rows="5" cols="50" placeholder="Enter information about the publisher/studio here.." required></textarea>

        <label for="gameplay">Gameplay:</label>
        <textarea id="gameplay" name="gameplay" rows="5" cols="50" placeholder="Enter gameplay here.." required></textarea>

        <label for="image">Upload an Image (Max: 250KB):</label>
        <p id="file-upload-text" class="file-upload-text" placeholder="Choose an Image">Choose
            an Image</p>
        <p id="error" style="color: red;"></p>
        <label for="image" class="btn primary-btn">Choose an Image</label>
        <input type="file" name="image" id="image" accept="image/*" class="btn primary-btn" hidden>

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" cols="50" placeholder="Enter text here.." required></textarea>
        
        <label for="history">History:</label>
        <textarea id="history" name="history" rows="5" cols="50" placeholder="Enter history here.." required></textarea><br>
        <input class="btn primary-btn" type="submit" value="Create Page">
    </form><br><br>
</body>

</html>

<?php include_once 'views/footer.php'; ?>
