<?php
// Start a session to manage user login state
session_start();

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
        $pageContent = nl2br($row['content']); // Convert newline characters to HTML line breaks
        $imagePath = $row['image_path']; // Retrieve the image path from the database (already in /uploads/)
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
        <?php include('header.php'); ?>
    </div><br>
    <br><main>
    <h1><?php echo $pageTitle; ?></h1>
    </main><br>
    <main>
    <img src="<?php echo $imagePath; ?>" alt="Uploaded Image">
    <p><?php echo $pageContent; ?></p>
    </main>
    <p>Created at: <?php echo $pageCreatedAt; ?></p>
</body>
</html>

