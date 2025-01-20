<?php include('../config/db.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password for security

    

    // Insert user data into the 'users' table
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");

    try {
        $stmt->execute(['username' => $username, 'password' => $password]);
        // echo "Registration successful. You can now <a href='login.php'>login</a>.";
        header('Location: login.php');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/create.css">
</head>
<body>

<h1>Register</h1>

<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
</form>

</body>
</html>
