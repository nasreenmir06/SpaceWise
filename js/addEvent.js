document.addEventListener('DOMContentLoaded', function() {
    populateDropdown('startHour', 1, 12);
    populateDropdown('endHour', 1, 12);
    populateDropdown('startMin', 0, 59);
    populateDropdown('endMin', 0, 59);

    const selectElement = document.getElementById('buildingSelect');
    const roomSelectElement = document.getElementById('roomSelect');
    const selectRoomDiv = document.getElementById('selectRoom');

    selectElement.addEventListener('change', function(event) {
        const selectedValue = event.target.value;
        if (selectedValue) {
            fetchRooms(selectedValue);
        }
    });

    function fetchRooms(buildingName) {
        fetch('addEvent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'building_name=' + encodeURIComponent(buildingName)
        })
        .then(response => response.json())
        .then(data => {
            roomSelectElement.innerHTML = '<option value="" disabled selected>Select Room</option>';
            data.forEach(room => {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomSelectElement.appendChild(option);
            });
            selectRoomDiv.style.display = 'block';
        })
        .catch(error => console.error('Error fetching rooms:', error));
    }
});

function populateDropdown(elementId, start, end) {
    const dropdown = document.getElementById(elementId);
    for (let i = start; i <= end; i++) {
        const option = document.createElement('option');
        option.value = i < 10 ? '0' + i : i;
        option.textContent = i < 10 ? '0' + i : i;
        dropdown.appendChild(option);
    }
}




