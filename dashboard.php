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
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css?v=0.0">
</head>
<body>
    <h1>Dashboard</h1>
    <?php if (is_null($environment_name) || $environment_name === "NULL") : ?>
        <h3>You have no spaces</h3>
        <button onclick="window.location.href='setEnvironment.php'" id="setEnivironment">Create a Space</button>
    <?php else : ?>
        <div id="search">
            <input type="text" placeholder="Search..">
            <select name="building_name" required>
                <option value="" disabled selected>What are you searching for?</option>
                <option value="event">Event</option>
                <option value="room">Room</option>
                <option value="building">Building</option>
            </select>
        </div>
        <button>Edit Space Setup</button>
        <h3>Your space: <?php echo htmlspecialchars($environment_name); ?></h3>
    <?php endif; ?>
</body>
</html>
