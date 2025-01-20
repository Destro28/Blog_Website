<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch posts created by the user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id");
$stmt->execute(['user_id' => $userId]);
$myPosts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<h1>My Posts</h1>

<?php if ($myPosts): ?>
    <?php foreach ($myPosts as $post): ?>
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <p><?php echo htmlspecialchars($post['content']); ?></p>
        <small>Posted on <?php echo $post['created_at']; ?></small>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>No posts created yet.</p>
<?php endif; ?>

<a href="index.php" class="back-to-home">Back to Home</a>

</body>
</html>
