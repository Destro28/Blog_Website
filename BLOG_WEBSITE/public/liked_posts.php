<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch posts liked by the user
$stmt = $pdo->prepare("
    SELECT p.*, pr.reaction 
    FROM posts p 
    JOIN post_reactions pr ON p.id = pr.post_id 
    WHERE pr.user_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$likedPosts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liked/Disliked Posts</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<h1>Liked/Disliked Posts</h1>

<?php if ($likedPosts): ?>
    <?php foreach ($likedPosts as $post): ?>
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <p><?php echo htmlspecialchars($post['content']); ?></p>
        <small>Posted on <?php echo $post['created_at']; ?></small>
        <p>Reaction: <?php echo htmlspecialchars($post['reaction']); ?></p>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>No liked or disliked posts yet.</p>
<?php endif; ?>

<a href="index.php">Back to Home</a>

</body>
</html>

