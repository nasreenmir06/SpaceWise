document.addEventListener('DOMContentLoaded', function() {
    populateDropdown('startHour', 1, 12);
    populateDropdown('endHour', 1, 12);
    populateDropdown('startMin', 0, 59);
    populateDropdown('endMin', 0, 59);

    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;

    const startDateInput = document.getElementById('startDate');
    startDateInput.setAttribute('min', formattedDate);
    startDateInput.setAttribute('value', formattedDate);

    const endDateInput = document.getElementById('endDate');
    endDateInput.setAttribute('min', formattedDate);
    endDateInput.setAttribute('value', formattedDate);

    const buildingSelectElement = document.getElementById('buildingSelect');
    const roomSelectElement = document.getElementById('roomSelect');
    const selectRoomDiv = document.getElementById('selectRoom');
    const createEventDiv = document.getElementById('createEvent');

    buildingSelectElement.addEventListener('change', function(event) {
        const selectedValue = buildingSelectElement.value;
        console.log(selectedValue);
        fetchRooms(selectedValue);
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
            createEventDiv.style.display = 'block';

        })
        .catch(error => console.error('Error fetching rooms:', error));
    }

    document.getElementById('createEventButton').addEventListener('click', function() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const startHour = document.getElementById('startHour').value;
        const startMin = document.getElementById('startMin').value;
        const startMeridiem = document.getElementById('startMeridiem').value;
        const endHour = document.getElementById('endHour').value;
        const endMin = document.getElementById('endMin').value;
        const endMeridiem = document.getElementById('endMeridiem').value;
        const building = document.getElementById('buildingSelect').value;
        const room = document.getElementById('roomSelect').value;
        const eventName = document.getElementById('eventName').value;

        const eventData = {
            startDate,
            endDate,
            startHour,
            startMin,
            startMeridiem,
            endHour,
            endMin,
            endMeridiem,
            building,
            room,
            eventName
        };

        fetch('addEvent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch(error => console.error('Error:', error));
    });
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




