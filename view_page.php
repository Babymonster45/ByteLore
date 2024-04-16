<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Checks if the user tagged remember me
include ('remember_me.php');

// Start a session to manage user login state
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establish a database connection
include ('/secure_config/config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the page ID from the URL
if (isset($_GET['id'])) {
    $pageID = $_GET['id'];

    // Retrieve the page from the database using the ID
    $sql = "SELECT user_pages.*, users.username FROM user_pages JOIN users ON user_pages.created_by = users.id WHERE user_pages.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pageID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $pageTitle = $row['title'];
        $pageContent = nl2br($row['content']); // Convert newline characters to HTML line breaks
        $imagePath = $row['image_path']; // Retrieve the image path from the database (already in /uploads/)
        $pageCreatedAt = $row['created_at'];
        $createdBy = $row['username']; // Retrieve the username of the user who created the page
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
    <title>
        <?php echo $pageTitle; ?>
    </title>
    <link rel="stylesheet" href="/home_page.css">
    <style>
        body {
            background-color: #67b3b5;
        }

        header {
            background-color: #67b3b5;
            padding: 20px 0;
        }

        header h1 {
            color: #fff;
            font-size: 36px;
            margin: 0;
        }

        .subheader {
            background-color: #fff;
            padding: 1px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="subheader">
        <?php include ('header.php'); ?>
    </div><br>
    <br>
    <main>
        <h1>
            <?php echo $pageTitle; ?>
        </h1>
    </main><br>
    <main>
        <img src="<?php echo $imagePath; ?>" alt="Uploaded Image">
        <p>
            <?php echo $pageContent; ?>
        </p>
    </main><br>
    <main>
        <?php
        // Show the Edit button to admins, editors, and the creator of the page
        if (isset($_SESSION['user_id']) && ($currentUserRole >= 1 || $_SESSION['user_id'] == $row['created_by'])) {
            echo '<div><a class="button" href="edit_page.php?id=' . $pageID . '">Edit Page</a></div>';
        }
        ?>
        <p>Created by
            <?php echo $createdBy; ?> at
            <?php echo $pageCreatedAt; ?>
        </p>
    </main><br>
</body>

</html>