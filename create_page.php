<?php
// Establish a database connection
$conn = new mysqli("localhost", "bytelord", "Chickennuggets#11269", "bytelore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Insert data into the database
    $sql = "INSERT INTO user_pages (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        // Get the ID of the newly created page
        $newPageID = $stmt->insert_id;

        // Close the prepared statement
        $stmt->close();

        // Close the database connection
        $conn->close();

        // Redirect the user to their new page
        header("Location: view_page.php?id=$newPageID"); // Change view_page.php to your actual page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and database connection if an error occurred
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>
    <link rel="stylesheet" href="/home_page.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            background-color: #67b3b5;
            color: #fff;
            padding: 20px 0;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin: 10px 0;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px 0;
        }

        input[type="submit"] {
            display: inline-block;
            padding: 10px 20px;
            background-color: #67b3b5;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #4a8a8c;
        }
    </style>
</head>
<body>
    <h1>Create a New Page</h1>
    <form action="create_page.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Megaman" required>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" cols="50" placeholder="Enter text here.." required></textarea>
        <input type="submit" value="Create Page">
    </form>
    <a href="/">Home Page</a>
</body>
</html>

