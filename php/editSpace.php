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
$space_name = $_SESSION['space_name'];

function fetchBuildings($username, $space_name, $conn) {
    $buildings = [];
    $fetchBuildingsSQL = "SELECT buildings FROM `${username}_${space_name}`";
    $result = $conn->query($fetchBuildingsSQL);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $buildings[] = $row['buildings'];
        }
    }
    echo json_encode($buildings);
    exit();
}

function fetchRooms($username, $space_name, $building_name, $conn) {
    $rooms = [];
    $fetchRoomsSQL = "SELECT rooms FROM `${username}_${space_name}_${building_name}`";
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
    if (isset($_POST['BuildingOrRoom'])) {
        $buildingOrRoom = $_POST['BuildingOrRoom'];
        if ($buildingOrRoom === 'building') {
            fetchBuildings($username, $space_name, $conn);
        } elseif ($buildingOrRoom === 'room' && isset($_POST['building_name'])) { 
            $building_name = $_POST['building_name']; 
            fetchRooms($username, $space_name, $building_name, $conn);
        }
    } else {
        if (isset($_POST['AddOrRemove']) && isset($_POST['BuildingOrRoom'])) {
            $addOrRemove = $_POST['AddOrRemove'];
            $buildingOrRoom = $_POST['BuildingOrRoom'];
            $building_name = isset($_POST['building_name']) ? $_POST['building_name'] : '';
            $room_name = isset($_POST['room_name']) ? $_POST['room_name'] : '';

            // For demonstration, send the data back as a response
            $response = "AddOrRemove: $addOrRemove\nBuildingOrRoom: $buildingOrRoom\nBuilding Name: $building_name\nRoom Name: $room_name";
            echo nl2br($response);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Space</title>
    <link rel="stylesheet" href="../css/style.css?v=0.0">
</head>
<body>
    <h1>Edit Space</h1>
    <form id="editSpaceForm" action="editSpace.php" method="POST">
        <div id="editSpaceOptions">
            <select id="AddOrRemove" name="AddOrRemove">
                <option value="" disabled selected>Add or Remove?</option>
                <option value="add">Add</option>
                <option value="remove">Remove</option>
            </select>
            <select id="BuildingOrRoom" name="BuildingOrRoom">
                <option value="" disabled selected>Building or Room?</option>
                <option value="building">Building</option>
                <option value="room">Room</option>
            </select>
        </div>
        <div id="editSpaceElems">
            <div id="buildingDropdownDiv" name="buildingName" style="display:none;">
                <select id="buildingDropdown">
                    <option value="" disabled selected>Select Building</option>
                </select>
            </div>
            <div id="roomDropdownDiv" style="display:none;">
                <select id="roomDropdown">
                    <option value="" disabled selected>Select Room</option>
                </select>
            </div>
            <div id="roomNameDiv" style="display:none;">
                <input type="text" id="roomName" placeholder="What is the name of this new room?">
            </div>
        </div>
        <button type="submit">Submit</button>
    </form>
    <script src="../js/editSpace.js"></script>
</body>
</html>


