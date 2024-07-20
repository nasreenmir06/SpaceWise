<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$username = $_SESSION['username'];
$tableName = "{$username}_events";
$sql = "SELECT * FROM $tableName";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>All Events</h1>
    <?php if (isset($result) && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Building</th>
                <th>Room</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['eventName']); ?></td>
                    <td><?php echo htmlspecialchars($row['building']); ?></td>
                    <td><?php echo htmlspecialchars($row['room']); ?></td>
                    <td><?php echo htmlspecialchars($row['startDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['endDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['startTime']); ?></td>
                    <td><?php echo htmlspecialchars($row['endTime']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no events</p>
    <?php endif; ?>
    <button onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</body>
</html>

<?php
$conn->close();
?>