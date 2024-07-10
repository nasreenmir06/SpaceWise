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
            <input type="text" placeholder="What is the name of this space?">
            <button type="submit">Set Space name</button>
            <span id="spaceNameSubtext">Ex. Queen's University</span>
        </div>
        <div class="form-row">
            <h2>Buildings</h2>
            <input type="text" placeholder="Enter building name">
            <button type="submit">Add building</button>
        </div>
        <div class="form-row">
            <h2>Rooms</h2>
            <input type="text" placeholder="Enter room name">
            <button type="submit">Add room</button>
        </div>
        <div class="form-row">
            <button type="submit" id="createSpace">Create Space!</button>
        </div>
    </form>
</body>
</html>
