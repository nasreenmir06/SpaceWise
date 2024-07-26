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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
    <style>
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f6f6f6;
            min-width: 230px;
            border: 1px solid #ddd;
            z-index: 1;
        }

        .dropdown-content div {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content div:hover {
            background-color: #ddd;
        }

        .show {
            display: block;
        }
    </style>
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
    <div id="selectRoom" style="display:none;" style="display:none;">
        <h3>Select Room</h3>
        <select name="roomSelect" id="roomSelect" required>
            <option value="" disabled selected>Select Room</option>
        </select>
    </div>  
    <script src="../js/editEvent.js"></script>
</body>
</html>



