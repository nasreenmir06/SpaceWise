<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $sql = "INSERT INTO login_info (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Register</h1>
    <form method="post" action="register.php">
        <label for="username">Enter a username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Choose a password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
