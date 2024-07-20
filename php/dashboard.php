<?php
session_start();
include 'db.php';

$username = $_SESSION['username'];

$query = "SELECT environment_Name FROM login_info WHERE username = '$username'";
$result = $conn->query($query);

$environmentName = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $environmentName = $row['environment_Name'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Dashboard</h1>
    <?php if (is_null($environmentName) || $environmentName === "NULL") : ?>
        <h3>You have no spaces</h3>
        <button onclick="window.location.href='setEnvironment.php'" id="setEnivironment">Create a Space</button>
    <?php else : ?>
        <form id="search-form" method="GET">
            <input id="searchBox" type="text" name="query" placeholder="Search..">
            <select name="searchType" id="searchType" required>
                <option value="" disabled selected>What are you searching for?</option>
                <option value="event">Event</option>
                <option value="room">Room</option>
                <option value="building">Building</option>
            </select>
            <button type="submit">Search</button>
        </form>
        <div id="buttonOptions">
            <button onclick="window.location.href='addEvent.php'">Add Event</button>
            <button onclick="window.location.href='allEvents.php'">All Events</button>
            <button>Edit Space Setup</button>
        </div>
        <h3>Upcoming events in <?php echo htmlspecialchars($environmentName); ?></h3>
    <?php endif; ?>
    <script>
        document.getElementById('search-form').addEventListener('submit', function(event) {
            const searchType = document.getElementById('searchType').value;
            const query = document.getElementById('searchBox').value;
            if (searchType === '') {
                event.preventDefault();
                alert('Please select what you are searching for.');
            }
            else if (searchType === 'building') {
                event.target.action = 'buildingSearch.php';
            } else if (searchType === 'room') {
                event.target.action = 'roomSearch.php';
            } else if (searchType === 'event') {
                event.target.action = 'eventSearch.php';
            }
        });
    </script>
</body>
</html>
