<?php
session_start();
include('../config/db.php');

// Get the post ID from the URL
$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;

// Check if the comments table for this post exists, if not, create it
$table_name = "comments_" . $post_id;
$stmt = $pdo->prepare("SHOW TABLES LIKE :table_name");
$stmt->execute(['table_name' => $table_name]);
if ($stmt->rowCount() == 0) {
    $create_table_query = "CREATE TABLE $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        comment TEXT NOT NULL,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($create_table_query);
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    $insert_comment_query = "INSERT INTO $table_name (comment, user_id) VALUES (:comment, :user_id)";
    $stmt = $pdo->prepare($insert_comment_query);
    $stmt->execute(['comment' => $comment, 'user_id' => $user_id]);

    // Redirect back to the comments.php page
    header("Location: comments.php?post_id=$post_id");
    exit;
}

// Fetch and display comments for the current post
$select_comments_query = "SELECT c.*, u.username 
                          FROM $table_name c
                          JOIN users u ON c.user_id = u.id
                          ORDER BY c.created_at ASC";
$stmt = $pdo->prepare($select_comments_query);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments - <?php echo $post_id; ?></title>
    <link rel="stylesheet" type="text/css" href="../css/create.css">
</head>
<body>
    <h1>Comments for Post #<?php echo $post_id; ?></h1><br>
    <a href="index.php" class="back-to-home2">Back to Home</a> 
    <div class="comments-section">
        <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <strong><?php echo htmlspecialchars($comment['username']); ?></strong> commented on <?php echo $comment['created_at']; ?>:
            <p><?php echo htmlspecialchars($comment['comment']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="comment-form">
        <h2>Leave a comment</h2>
        <form method="POST" action="">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <textarea name="comment" rows="3" placeholder="Write your comment here..."></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php endif; ?>
    <a href="index.php" class="back-to-home2">
        Back to Home</a> 
</body>
</html>