<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="../css/style.css?v=0.1">
</head>
<body>
    <div id="startTime">
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
    <div id="endTime">
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
    <script src="../js/addEvent.js"></script>
</body>
</html>
