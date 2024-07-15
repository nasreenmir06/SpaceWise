<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['space_name'])) {
    header('Location: signin.php');
    exit();
}

$space_name = $_SESSION['space_name'];
$buildings = [];

$fetchBuildingsSQL = "SELECT buildings FROM `$space_name`";
$result = $conn->query($fetchBuildingsSQL);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buildings[] = $row['buildings'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['building_name'])) {
    $building_name = $conn->real_escape_string($_POST['building_name']);
    $building_table = "{$space_name}_{$building_name}";
    $rooms = [];

    $fetchRoomsSQL = "SELECT rooms FROM `$building_table`";
    $result = $conn->query($fetchRoomsSQL);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row['rooms'];
        }
    }

    echo json_encode($rooms);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Add Event</h1>
    <div id="startTime">
        <h3>Select Start Time</h3>
        <select name="startHour" id="startHour" required>
            <option value="" disabled selected>Hour</option>
        </select>
        <select name="startMin" id="startMin" required>
            <option value="" disabled selected>Minute</option>
        </select>
        <select name="startMeridiem" id="startMeridiem" required>
            <option value="" disabled selected>AM/PM</option>
            <option value="am">AM</option>
            <option value="pm">PM</option>
        </select>
    </div>

    <div id="endTime">
        <h3>Select End Time</h3>
        <select name="endHour" id="endHour" required>
            <option value="" disabled selected>Hour</option>
        </select>
        <select name="endMin" id="endMin" required>
            <option value="" disabled selected>Minute</option>
        </select>
        <select name="endMeridiem" id="endMeridiem" required>
            <option value="" disabled selected>AM/PM</option>
            <option value="am">AM</option>
            <option value="pm">PM</option>
        </select>
    </div>  

    <div id="selectBuilding">
        <h3>Select Building</h3>
        <select name="buildingSelect" id="buildingSelect" required>
            <option value="" disabled selected>Select Building</option>
            <?php foreach ($buildings as $building): ?>
                <option value="<?php echo htmlspecialchars($building); ?>"><?php echo htmlspecialchars($building); ?></option>
            <?php endforeach; ?>
        </select>
    </div>  

    <div id="selectRoom" style="display:none;">
        <h3>Select Building</h3>
        <select name="roomSelect" id="roomSelect" required>
            <option value="" disabled selected>Select Room</option>
        </select>
    </div>  
    <script src="../js/addEvent.js"></script>
</body>
</html>
