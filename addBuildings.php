<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['space_name'])) {
    header('Location: signin.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['building_name'])) {
    $building_name = $conn->real_escape_string($_POST['building_name']);
    $space_name = $_SESSION['space_name'];

    $insertBuildingSQL = "INSERT INTO `$space_name` (buildings) VALUES ('$building_name')";

    if ($conn->query($insertBuildingSQL) === TRUE) {
        $createBuildingTableSQL = "CREATE TABLE `{$space_name}_{$building_name}` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            rooms VARCHAR(255) NOT NULL
        )";

        if ($conn->query($createBuildingTableSQL) === TRUE) {
            echo "<script>alert('Building added and table created: " . $building_name . "');</script>";
        } else {
            echo "<script>alert('Error creating building table: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error adding building: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Buildings</title>
    <link rel="stylesheet" href="style.css?v=0.1">
</head>
<body>
    <h1>Add Buildings</h1>
    <form method="post" action="addBuildings.php" class="form-container">
        <div class="form-row">
            <h2>Buildings</h2>
            <input type="text" name="building_name" placeholder="Enter building name" required>
            <button type="submit">Add building</button>
            <button onclick="window.location.href='addRooms.php'" type="submit">Next</button>
        </div>
    </form>
</body>
</html>
