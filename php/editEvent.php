<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['space_name'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['username'];
$events = [];

$fetchEventsSQL = "SELECT eventName FROM `${username}_events`";
$result = $conn->query($fetchEventsSQL);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row['eventName'];
    }
}

$space_name = $_SESSION['space_name'];
$buildings = [];

$fetchBuildingsSQL = "SELECT buildings FROM `${username}_${space_name}`";
$result = $conn->query($fetchBuildingsSQL);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $buildings[] = $row['buildings'];
    }
}

if (isset($_GET['eventName'])) {
    $eventName = $conn->real_escape_string($_GET['eventName']);
    $fetchEventDetailsSQL = "SELECT * FROM `${username}_events` WHERE eventName='$eventName'";
    $result = $conn->query($fetchEventDetailsSQL);

    if ($result->num_rows > 0) {
        $eventDetails = $result->fetch_assoc();
        echo json_encode($eventDetails);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Event not found']);
    }
    exit();
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
        $origEventName = $conn->real_escape_string($data['origEventName']);

        $sql = "UPDATE `${username}_events` 
        SET eventName = '$eventName',
            building = '$building', 
            room = '$room', 
            startDate = '$startDate', 
            endDate = '$endDate', 
            startTime = '$startHour:$startMin $startMeridiem', 
            endTime = '$endHour:$endMin $endMeridiem' 
        WHERE eventName = '$origEventName'";

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
    <title>Edit Event</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Edit Event</h1>
    <div class="dropdown">
        <input type="text" placeholder="Search events..." id="myInput" onkeyup="filterFunction()">
        <div id="myDropdown" class="dropdown-content">
            <?php foreach ($events as $event): ?>
                <div onclick="selectEvent('<?php echo htmlspecialchars($event); ?>')">
                    <?php echo htmlspecialchars($event); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>  
    <div id="setEventName" style="display:none;">
        <h3>Set Event Name</h3>
        <input type="text" id="eventName" placeholder="Enter event name">
    </div>
    <div id="startDateDiv" style="display:none;">
        <h3>Select Start Date</h3>
        <input type="date" id="startDate" name="startDate" />
    </div>
    <div id="endDateDiv" style="display:none;">
        <h3>Select End Date</h3>
        <input type="date" id="endDate" name="endDate" />
    </div>
    <div id="startTime" style="display:none;">
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
    <div id="endTime" style="display:none;">
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

    <div id="selectBuilding" style="display:none;">
        <h3>Select Building</h3>
        <select name="buildingSelect" id="buildingSelect" required>
            <option value="" disabled selected>Select Building</option>
            <?php foreach ($buildings as $building): ?>
                <option value="<?php echo htmlspecialchars($building); ?>"><?php echo htmlspecialchars($building); ?></option>
            <?php endforeach; ?>
        </select>
    </div>  
    <div id="selectRoom" style="display:none;">
        <h3>Select Room</h3>
        <select name="roomSelect" id="roomSelect" required>
            <option value="" disabled selected>Select Room</option>
        </select>
    </div>  
    <div id="updateEvent" style="display:none;">
        <button type="submit" id="updateEventButton">Update Event</button>
    </div>
    <div id="backToDashboardDiv">
        <button id="backToDashboard" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </div>
    <script src="../js/editEvent.js"></script>
</body>
</html>



