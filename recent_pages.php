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
        $createdAt = $row['created_at'];
        $recentPages[] = "<div class='col-lg-4 col-md-8 col-sm-10'><div class='single-blog blog-style-one'><div class='blog-image'><a href='view_page.php?id=$pageID'><img src='$imagePath' alt='$pageTitle'></a></div><div class='blog-content'><h5 class='blog-title'><a href='view_page.php?id=$pageID'>$pageTitle</a></h5><span><i class='lni lni-calendar'></i> $createdAt</span></div></div></div>";
    }
}

// Close the database connection
$conn->close();
?>

<!-- Display the recent pages -->
<div class="blog-area pb-5">
   <div class="container">
      <div class="row justify-content-center">
        <?php
        foreach ($recentPages as $page) {
            echo $page;
        }
        ?>
      </div>
   </div>
</div>
