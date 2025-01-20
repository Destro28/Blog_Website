<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blog_db';

try {
    // Create connection to MySQL server
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");

    // Select the database
    $pdo->exec("USE $dbname");

    // Create the 'posts' table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        file_data LONGBLOB,
        likes INT DEFAULT 0,
        dislikes INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create the 'users' table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create the 'post_reactions' table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS post_reactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        post_id INT NOT NULL,   
        reaction ENUM('like', 'dislike') NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
        UNIQUE (user_id, post_id)  -- Ensure a user can react to a post only once
    )");


    // //creating uploads table that stores the image/video attributes
    // $pdo->exec("CREATE TABLE IF NOT EXISTS uploads (
    // id INT AUTO_INCREMENT PRIMARY KEY,
    // file_name VARCHAR(255) NOT NULL,
    // file_type VARCHAR(50),
    // file_path VARCHAR(255) NOT NULL,
    // upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP unecessary now 

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
