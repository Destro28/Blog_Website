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
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID

    // Prepare and execute the SQL statement to insert the new post
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, id) VALUES (:title, :content, :id)");
    $stmt->execute(['title' => $title, 'content' => $content, 'id' => $userId]);

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
    
    <label for="content">Content</label>
    <textarea id="content" name="content" required></textarea>
    <br><br>
    <button type="submit">Create</button>
</form>

<a href="index.php" class="back-to-home">Back to Home</a>

</body>
</html>
