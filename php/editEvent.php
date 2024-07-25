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
    <script src="../js/editEvent.js"></script>
</body>
</html>



