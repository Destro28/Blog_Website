<?php include('../config/db.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashing the password for security

    // Server-side validation
    $errors = [];
    
    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long.";
    }
    
    if (strlen($password) < 4 || !preg_match('/[^a-zA-Z\d]/', $password)) {
        $errors[] = "Password must be at least 4 characters long and contain at least one special character.";
    }

    // If there are no errors, proceed with the registration
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");

        try {
            $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
            header('Location: login.php');
            exit();
        } catch (PDOException $e){
            // Check if the error is due to a duplicate username
            if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for unique constraint violations
                echo "<script>alert('Error: Username already exists.');</script>";
            } else {
                // General database error
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
            }
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
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
    <input type="text" id="username" name="username" required pattern=".{4,}" title="Username must be at least 4 characters long">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required pattern="(?=.*[^a-zA-Z\d]).{4,}" title="Password must be at least 4 characters long and contain at least one special character">

    <button type="submit">Register</button>
</form>

<a href="index.php" class="back-to-home">Back to Home</a>

</body>
</html>
