<?php
session_start();
include('../config/db.php');
// Function to fetch post reactions for the logged-in user
function getUserReaction($pdo, $userId, $postId) {
    $stmt = $pdo->prepare("SELECT reaction FROM post_reactions WHERE user_id = :user_id AND post_id = :post_id");
    $stmt->execute(['user_id' => $userId, 'post_id' => $postId]);
    return $stmt->fetchColumn();
}

// Fetch posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY likes DESC, created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mindscape:Landing Page</title>
    <link rel="stylesheet" type="text/css" href="../css/index.css">
</head>
<body>

<h1>Welcome<?php echo isset($_SESSION['username']) ? ', ' . htmlspecialchars($_SESSION['username']) : ' to Mindscape!'; ?></h1>

<!-- Login/Logout Button -->
<?php if (isset($_SESSION['user_id'])): ?>
    <!-- when user_id is not null in the session array and can be used -->
    <a href="logout.php">
        <button>Logout</button>
    </a>
    <a href="create_2.0.php">
        <button>Create</button>
    </a>
    <a href="my_posts.php"><button>My Posts</button></a>
    <a href="liked_posts.php"><button>My responses</button></a>
<?php else: ?>  
    <!-- Buttons that are displayed when user has not logged in -->
    <a href="login.php">
        <button>Login</button>
    </a>
    <a href="register_2.0.php">
        <button>Register</button>
    </a>
<?php endif; ?>

<hr>


<?php while ($row = $stmt->fetch()): 
    $userReaction = isset($_SESSION['user_id']) ? getUserReaction($pdo, $_SESSION['user_id'], $row['id']) : null;
?>
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <!-- This is where u will need the image to be -->    
    




    <p><?php echo  ($row['content']); ?></p>
    <small>Posted on <?php echo $row['created_at']; ?> | Likes: <?php echo $row['likes']; ?> | Dislikes: <?php echo $row['dislikes']; ?></small>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST" action="react.php">
            <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="reaction" value="like" <?php echo $userReaction === 'like' ? 'disabled' : ''; ?>>Like</button>
            <button type="submit" name="reaction" value="dislike" <?php echo $userReaction === 'dislike' ? 'disabled' : ''; ?>>Dislike</button>
        </form>


        <a href="comments.php?post_id=<?php echo $row['id']; ?>">
            <button>Comments</button>
        </a>
    <?php endif; ?>
    
    <hr>
<?php endwhile; ?>

</body>
</html>
