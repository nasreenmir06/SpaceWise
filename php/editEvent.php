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

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row['eventName'];
        }
    } else {
        echo "No events found.";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
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
    <div id="selectEvent">
        <h3>Select Event</h3>
        <select name="eventSelect" id="eventSelect" required>
            <option value="" disabled selected>Select Event</option>
            <?php foreach ($events as $event): ?>
                <option value="<?php echo htmlspecialchars($event); ?>"><?php echo htmlspecialchars($event); ?></option>
            <?php endforeach; ?>
        </select>
    </div>  
    <script src="../js/editEvent.js"></script>
</body>
</html>


