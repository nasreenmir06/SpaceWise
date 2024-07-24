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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['building_name'])) {
    $building_name = $conn->real_escape_string($_POST['building_name']);
    $building_table = "${username}_{$space_name}_{$building_name}";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data) {
        $eventName = $conn->real_escape_string($data['eventName']);
        $building = $conn->real_escape_string($data['building']);
        $room = $conn->real_escape_string($data['room']);
        $startDate = $conn->real_escape_string($data['startDate']);
        $endDate = $conn->real_escape_string($data['endDate']);
        $startHour = $conn->real_escape_string($data['startHour']);
        $startMin = $conn->real_escape_string($data['startMin']);
        $startMeridiem = $conn->real_escape_string($data['startMeridiem']);
        $endHour = $conn->real_escape_string($data['endHour']);
        $endMin = $conn->real_escape_string($data['endMin']);
        $endMeridiem = $conn->real_escape_string($data['endMeridiem']);

        $sql = "INSERT INTO `${username}_events` (eventName, building, room, startDate, endDate, startTime, endTime)
                VALUES ('$eventName', '$building', '$room', '$startDate', '$endDate', 
                        '$startHour:$startMin $startMeridiem', '$endHour:$endMin $endMeridiem')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
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
    <p>If this event is a one day event, your start date and end date should be the same.</p>
    <div id="startDateDiv">
        <h3>Select Start Date</h3>
        <input type="date" id="startDate" name="startDate" />
    </div>

    <div id="endDateDiv">
        <h3>Select End Date</h3>
        <input type="date" id="endDate" name="endDate" />
    </div>

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

    <div id="setEventName" style="display:none;">
        <h3>Set Event Name</h3>
        <input type="text" id="eventName" placeholder="Enter event name">
    </div>

    <div id="createEvent" style="display:none;">
        <button onclick="window.location.href='addEvent.php'" type="submit" id="createEventButton">Create Event!</button>
    </div>
    <script src="../js/addEvent.js"></script>
</body>
</html>
