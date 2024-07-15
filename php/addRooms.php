<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['space_name'])) {
    header('Location: signin.php');
    exit();
}

$space_name = $_SESSION['space_name'];
$username = $_SESSION['username'];
$buildings = [];

$fetchBuildingsSQL = "SELECT buildings FROM `${username}_${space_name}`";
$result = $conn->query($fetchBuildingsSQL);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buildings[] = $row['buildings'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rooms']) && isset($_POST['building_name'])) {
    $rooms = $conn->real_escape_string($_POST['rooms']);
    $building_name = $conn->real_escape_string($_POST['building_name']);
    $building_table = "${username}_{$space_name}_{$building_name}";

    $insertRoomSQL = "INSERT INTO `$building_table` (rooms) VALUES ('$rooms')";

    if ($conn->query($insertRoomSQL) === TRUE) {
        echo "<script>alert('Room added: " . $rooms . "');</script>";
    } else {
        echo "<script>alert('Error adding room: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rooms</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Add Rooms</h1>
    <form method="post" action="addRooms.php" class="form-container">
        <div class="form-row">
            <h2>Rooms</h2>
            <label for="building_name">Select Building:</label>
            <select name="building_name" required>
                <option value="" disabled selected>Select building</option>
                <?php foreach ($buildings as $building): ?>
                    <option value="<?php echo htmlspecialchars($building); ?>"><?php echo htmlspecialchars($building); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="rooms" placeholder="Enter room name" required>
            <button type="submit">Add Room</button>
        </div>
    </form>
    <button onclick="window.location.href='dashboard.php'" type="submit">Finish Creating Space!</button>
</body>
</html>
