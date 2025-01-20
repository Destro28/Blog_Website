<?php
session_start();
include('../config/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userId = $_SESSION['user_id'];

   

   $fileData = null;

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $fileTmpName = $file['tmp_name'];

        // Ensure the temporary file exists before reading it
        if (file_exists($fileTmpName)) {
            // Read the file contents
            $fileData = file_get_contents($fileTmpName);

            if ($fileData === false) {
                echo "Failed to read file contents.";
                // Optional: handle the error, set fileData to null, etc.
                
            }
        } else {
            echo "Temporary file does not exist.";
            $fileData = null; // Ensure fileData is null if the file doesn't exist
        }
    } elseif (isset($_FILES['file']['error']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo "File upload error code: " . $_FILES['file']['error'];
        // No need for sleep here; just exit or redirect appropriately
        header("Location: index.php");
        exit; // Exit after redirect to prevent further execution
    }

// At this point, you should check if $fileData is still null
if ($fileData === null) {
    echo "No file data to insert into the database.";
    // You can choose to handle this situation (e.g., return or display an error)
}



    // Insert post and file data into the database
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, file_data) VALUES (:title, :content, :user_id, :file_data)");
    $stmt->execute([
        'title' => $title,
        'content' => $content,
        'user_id' => $userId,
        'file_data' => $fileData
    ]);

    // Redirect to the index page after successfully creating the post
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="../css/create.css">
</head>
<body>

<h1>Create a New Post</h1>
<br><br><br><br>
<form method="POST" action="">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" required>

    <!-- video or image -->

    
    
    <label for="content">Content</label>
    <textarea id="content" name="content" required></textarea><br>

    <label for="file">Choose file:</label>
        <input type="file" name="file" id="file">

    <br><br>
    <button type="submit">Create</button>
</form>

<a href="index.php" class="back-to-home">Back to Home</a>

</body>
</html>
