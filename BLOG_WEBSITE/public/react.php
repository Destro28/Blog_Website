<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$postId = $_POST['post_id']; // The ID of the post being reacted to
$reaction = $_POST['reaction']; // 'like' or 'dislike'

// Check the current reaction
$stmt = $pdo->prepare("SELECT reaction FROM post_reactions WHERE user_id = :user_id AND post_id = :post_id");
$stmt->execute(['user_id' => $userId, 'post_id' => $postId]);
$currentReaction = $stmt->fetchColumn();

// Logic to handle likes and dislikes
if ($currentReaction) {
    // If the user already reacted
    if ($currentReaction == $reaction) {
        // If they click the sam    e reaction again, remove it
        $stmt = $pdo->prepare("DELETE FROM post_reactions WHERE user_id = :user_id AND post_id = :post_id");
        $stmt->execute(['user_id' => $userId, 'post_id' => $postId]);
        
        // Update the counts
        if ($reaction == 'like') {
            $stmt = $pdo->prepare("UPDATE posts SET likes = likes - 1 WHERE id = :post_id");
        } else {
            $stmt = $pdo->prepare("UPDATE posts SET dislikes = dislikes - 1 WHERE id = :post_id");
        }
        $stmt->execute(['post_id' => $postId]);
    } else {
        // Update to the new reaction
        $stmt = $pdo->prepare("UPDATE post_reactions SET reaction = :reaction WHERE user_id = :user_id AND post_id = :post_id");
        $stmt->execute(['reaction' => $reaction, 'user_id' => $userId, 'post_id' => $postId]);

        // Adjust the counts
        if ($reaction == 'like') {
            $stmt = $pdo->prepare("UPDATE posts SET likes = likes + 1, dislikes = dislikes - 1 WHERE id = :post_id");
        } else {
            $stmt = $pdo->prepare("UPDATE posts SET dislikes = dislikes + 1, likes = likes - 1 WHERE id = :post_id");
        }
        $stmt->execute(['post_id' => $postId]);
    }
} else {
    // Insert new reaction
    $stmt = $pdo->prepare("INSERT INTO post_reactions (user_id, post_id, reaction) VALUES (:user_id, :post_id, :reaction)");
    $stmt->execute(['user_id' => $userId, 'post_id' => $postId, 'reaction' => $reaction]);

    // Update the counts
    if ($reaction == 'like') {
        $stmt = $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id = :post_id");
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET dislikes = dislikes + 1 WHERE id = :post_id");
    }
    $stmt->execute(['post_id' => $postId]);
}

// Redirect back to the index page
header('Location: index.php');
?>
