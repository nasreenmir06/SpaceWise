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
        echo "<script>alert('Building added: " . $building_name . "');</script>";
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
        </div>
    </form>
    <form action="rooms.php" method="get">
        <button type="submit">Next</button>
    </form>
</body>
</html>
