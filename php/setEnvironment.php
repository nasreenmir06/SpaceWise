<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['space_name'])) {
    $space_name = $conn->real_escape_string($_POST['space_name']);
    $_SESSION['space_name'] = $space_name;

    $username = $_SESSION['username'];
    $sql = "UPDATE login_info SET environment_name='$space_name' WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        $createTableSQL = "CREATE TABLE `${username}_${space_name}` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            buildings VARCHAR(255) NOT NULL
        )";

        if ($conn->query($createTableSQL) === TRUE) {
            header('Location: addBuildings.php');
        } else {
            echo "<script>alert('Error creating environment table: " . $conn->error . "');</script>";
        }

        $createTableSQL = "CREATE TABLE `${username}_events` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            eventName VARCHAR(255) NOT NULL,
            building VARCHAR(255) NOT NULL,
            room VARCHAR(255) NOT NULL,
            startDate VARCHAR(255) NOT NULL,
            endDate VARCHAR(255) NOT NULL,
            startTime VARCHAR(255) NOT NULL,
            endTime VARCHAR(255) NOT NULL
        )";

        if ($conn->query($createTableSQL) === TRUE) {
            header('Location: addBuildings.php');
            exit();
        } else {
            echo "<script>alert('Error creating events table');</script>";
        }
    } else {
        echo "<script>alert('Error updating environment name: " . $conn->error . "');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Environment Name</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Create A Space</h1>
    <form method="post" action="setEnvironment.php" class="form-container">
        <div class="form-row">
            <h2>Name your Space!</h2>
            <input type="text" name="space_name" placeholder="What is the name of this space?" required>
            <span id="spaceNameSubtext">Ex. Queen's University</span>
            <button onclick="window.location.href='addBuildings.php'" type="submit">Next</button>
        </div>
    </form>
</body>
</html>
