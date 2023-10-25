<!-- ... your existing PHP code ... -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>
    <link rel="stylesheet" href="/create_page.css">
</head>
<body>
    <h1>Create a New Page</h1>
    <form action="create_page.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Megaman" required>

        <label for="image">Upload an Image (Max: 250KB):</label>
        <input type="file" name="image" id="image" accept="image/*" class="custom-file-input">
        <p id="file-upload-text" class="file-upload-text">Choose an Image</p>
        <label for="image" class="custom-file-label">Choose an Image</label>
        <input type="file" name="image" id="image" accept="image/*" class="custom-file-input">

        <label for="content">Content:</label>
        <textarea id="content" name "content" rows="10" cols="50" placeholder="Enter text here.." required></textarea>
        <input type="submit" value="Create Page">
    </form>
    <a href="/">Home Page</a>

    <script>
        // JavaScript to show the selected file name in the "Choose an Image" text
        const fileInput = document.getElementById("image");
        const fileUploadText = document.getElementById("file-upload-text");

        fileInput.addEventListener("change", function () {
            fileUploadText.textContent = this.files[0].name;
        });
    </script>
</body>
</html>
