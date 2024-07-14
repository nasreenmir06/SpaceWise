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
    <link rel="stylesheet" href="style.css?v=0.1">
</head>
<body>
    <h1>Dashboard</h1>
    <?php if (is_null($environment_name) || $environment_name === "NULL") : ?>
        <h3>You have no spaces</h3>
        <button onclick="window.location.href='setEnvironment.php'" id="setEnivironment">Create a Space</button>
    <?php else : ?>
        <form id="search-form" method="GET">
            <input type="text" name="query" placeholder="Search..">
            <select name="search_type" id="search_type" required>
                <option value="" disabled selected>What are you searching for?</option>
                <option value="event">Event</option>
                <option value="room">Room</option>
                <option value="building">Building</option>
            </select>
        </form>
        <button>Edit Space Setup</button>
        <h3>Upcoming events in <?php echo htmlspecialchars($environment_name); ?></h3>
    <?php endif; ?>
    <script>
        document.getElementById('search-form').addEventListener('submit', function(event) {
            const searchType = document.getElementById('search_type').value;
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
