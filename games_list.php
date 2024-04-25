<?php
// Checks if the user tagged remember me
include ('remember_me.php');

// Establish a database connection
include ('/secure_config/config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search form is submitted
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Construct a SQL query to search for titles
    $sql = "SELECT title, id FROM user_pages WHERE title LIKE ? ORDER BY title ASC";
    $stmt = $conn->prepare($sql);

    // Use "%" to allow searching for titles that contain the search term
    $searchTerm = '%' . $searchTerm . '%';

    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pages = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $pages = array();
    }
} else {
    // If no search is performed, show all pages
    $sql = "SELECT title, id FROM user_pages ORDER BY title ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pages = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $pages = array();
    }
}

include 'views/pageBuilder.php';
include 'views/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games List</title>
</head>

<body>
    <section class="call-action-area call-action-four">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="call-action-content text-center">
                        <h2 class="action-title">Game List</h2>
                        <div class="call-action-form">
                            <form method="get" action="games_list.php">
                                <input type="text" id="search" name="search" placeholder="Search for a game">
                                <div class="action-btn rounded-buttons">
                                    <button class="btn primary-btn rounded-full" type="submit">
                                        search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .blog-link:hover .blog-title {
            color: blue;
        }
    </style>

    <section class="blog-area pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <?php foreach ($pages as $page): ?>
                    <div class="col-lg-4 col-md-8 col-sm-10">
                        <a href="view_page.php?id=<?php echo $page['id']; ?>" class="blog-link"
                            style="text-decoration: none; color: inherit;">
                            <div class="single-blog blog-style-one">
                                <div class="blog-content">
                                    <h5 class="blog-title">
                                        <?php echo $page['title']; ?>
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</body>

</html>
<?php include_once 'views/footer.php'; ?>