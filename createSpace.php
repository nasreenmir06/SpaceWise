<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];

    if (isset($_POST['action']) && $_POST['action'] == 'set_space_name' && isset($_POST['space_name'])) {
        $space_name = $conn->real_escape_string($_POST['space_name']);

        $sql = "UPDATE login_info SET environment_name='$space_name' WHERE username='$username'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Environment name set successfully');</script>";
        } else {
            echo "<script>alert('Error updating environment name: " . $conn->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css?v=0.1">
</head>
<body>
    <h1>Create A Space</h1>
    <form method="post" action="createSpace.php" class="form-container">
        <div class="form-row">
            <h2>Name your Space!</h2>
            <input type="text" name="space_name" placeholder="What is the name of this space?">
            <span id="spaceNameSubtext">Ex. Queen's University</span>
            <button type="submit" name="action" value="set_space_name">Set Space name</button>
        </div>
        <div class="form-row">
            <h2>Buildings</h2>
            <input type="text" name="building_name" placeholder="Enter building name">
            <button type="submit" name="action" value="add_building">Add building</button>
        </div>
        <div class="form-row">
            <h2>Rooms</h2>
            <input type="text" name="room_name" placeholder="Enter room name">
            <button type="submit" name="action" value="add_room">Add room</button>
        </div>
        <div class="form-row">
            <button type="submit" id="createSpace" name="action" value="create_space">Create Space!</button>
        </div>
    </form>
</body>
</html>
