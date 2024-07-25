<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $username = $_SESSION['username'];
    $tableName = "{$username}_events";
    
    $stmt = $conn->prepare("SELECT * FROM $tableName WHERE building LIKE ?");
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Search Results</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <h1>Building Search Results</h1>
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
        <p>No results found for '<?php echo htmlspecialchars($searchQuery); ?>'</p>
    <?php endif; ?>
    <button onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</body>
</html>

<?php
$conn->close();
?>
