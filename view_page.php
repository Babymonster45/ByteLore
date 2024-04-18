<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('remember_me.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include ('/secure_config/config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $currentUserRole = $row['role'];
}

if (isset($_GET['id'])) {
    $pageID = $_GET['id'];

    $sql = "SELECT user_pages.*, users.username FROM user_pages JOIN users ON user_pages.created_by = users.id WHERE user_pages.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pageID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $pageTitle = $row['title'];
        $pageContent = nl2br($row['content']);
        $imagePath = $row['image_path'];
        $pageCreatedAt = $row['created_at'];
        $createdBy = $row['username'];
    } else {
        echo "Page not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid page ID.";
}

include_once 'views/pageBuilder.php';
include ('views/header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
</head>

<body>
    <div class="section-title-two">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <h2 class="fw-bold"><?php echo $pageTitle; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="about-us about-seven section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-12">
                    <div class="about-left">
                        <div class="section-title align-left">
                            <p><?php echo $pageContent; ?></p>
                            <div class="author">
                                <div class="content">
                                    <h5>
                                        <p>Created by
                                            <?php echo $createdBy; ?> at
                                            <?php echo $pageCreatedAt; ?>
                                        </p><br>
                                        <?php
                                        if (isset($_SESSION['user_id']) && ($currentUserRole >= 1 || $_SESSION['user_id'] == $row['created_by'])) {
                                            echo '<div><a class="btn primary-btn" href="edit_page.php?id=' . $pageID . '">Edit Page</a></div>';
                                        }
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="about-right">
                        <img src="<?php echo $imagePath; ?>" alt="Uploaded Image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once 'views/footer.php'; ?>
</body>

</html>