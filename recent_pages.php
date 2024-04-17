<?php
// Checks if the user tagged remember me
include('remember_me.php');

// Establish a database connection
include('/secure_config/config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the top 6 most recent pages
$sql = "SELECT * FROM user_pages ORDER BY created_at DESC LIMIT 6";
$result = $conn->query($sql);

$recentPages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pageTitle = $row['title'];
        $pageID = $row['id'];
        $imagePath = $row['image_path'];
        $recentPages[] = "<div><img src='$imagePath' alt='$pageTitle'><a class='button' href='view_page.php?id=$pageID'>$pageTitle</a></div>";
    }
}

// Close the database connection
$conn->close();
?>

<!-- Display the recent pages buttons -->
<div class="recent-pages">
    <?php
    foreach ($recentPages as $button) {
        echo $button;
    }
    ?>
</div>
