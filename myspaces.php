<?php
session_start();
include 'db.php';

$username = $_SESSION['username'];

$query = "SELECT environment_name FROM login_info WHERE username = '$username'";
$result = $conn->query($query);

$environment_name = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $environment_name = $row['environment_name'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Spaces</title>
    <link rel="stylesheet" href="style.css?v=0.0">
</head>
<body>
    <h1>My Spaces</h1>
    <?php if (is_null($environment_name) || $environment_name === "NULL") : ?>
        <h3>You have no spaces</h3>
    <?php else : ?>
        <h3>Your space: <?php echo htmlspecialchars($environment_name); ?></h3>
    <?php endif; ?>
</body>
</html>
