<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the top 3 most recent pages
$sql = "SELECT * FROM user_pages ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);

$recentPages = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pageTitle = $row['title'];
        $pageID = $row['id'];
        $recentPages[] = "<a class='button' href='view_page.php?id=$pageID'>$pageTitle</a>";
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

