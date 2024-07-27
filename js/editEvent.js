function filterFunction() {
    const input = document.getElementById('myInput');
    const filter = input.value.toUpperCase();
    const div = document.getElementById('myDropdown');
    const items = div.getElementsByTagName('div');

    for (let i = 0; i < items.length; i++) {
        const txtValue = items[i].textContent || items[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }

    if (input.value === "") {
        div.classList.remove('show');
    } else {
        div.classList.add('show');
    }
}

function fetchRooms(buildingName, roomInput) {
    return fetch('editEvent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'building_name=' + encodeURIComponent(buildingName)
    })
    .then(response => response.json())
    .then(data => {
        roomInput.innerHTML = '<option value="" disabled selected>Select Room</option>';
        data.forEach(room => {
            const option = document.createElement('option');
            option.value = room;
            option.textContent = room;
            roomInput.appendChild(option);
        });
        roomInput.style.display = 'block';
    })
    .catch(error => console.error('Error fetching rooms:', error));
}

function defaultValues(eventDetails) {
    populateDropdown('startHour', 1, 12);
    populateDropdown('endHour', 1, 12);
    populateDropdown('startMin', 0, 59);
    populateDropdown('endMin', 0, 59);

    const elementsToDisplay = [
        'setEventName', 'startDateDiv', 'endDateDiv', 'startTime', 
        'endTime', 'selectBuilding', 'selectRoom', 'updateEvent'
    ];
    elementsToDisplay.forEach(id => {
        const element = document.getElementById(id);
        element.style.display = 'block';
    });
    
    //fill in default values
    const startHour = eventDetails.startTime.substring(0, 2);
    const startMin = eventDetails.startTime.substring(3, 5);
    const startMer = eventDetails.startTime.substring(6, 8);
    const endHour = eventDetails.endTime.substring(0, 2);
    const endMin = eventDetails.endTime.substring(3, 5);
    const endMer = eventDetails.endTime.substring(6, 8);

    document.getElementById('eventName').value = eventDetails.eventName;
    document.getElementById('startDate').value = eventDetails.startDate;
    document.getElementById('endDate').value = eventDetails.endDate;
    document.getElementById('startHour').value = startHour;
    document.getElementById('startMin').value = startMin;
    document.getElementById('startMeridiem').value = startMer;
    document.getElementById('endHour').value = endHour;
    document.getElementById('endMin').value = endMin;
    document.getElementById('endMeridiem').value = endMer;
    document.getElementById('buildingSelect').value = eventDetails.building;

    const roomSelect = document.getElementById('roomSelect');
    fetchRooms(eventDetails.building, roomSelect)
        .then(() => {
            roomSelect.value = eventDetails.room;
        });
}

function selectEvent(value) {
    const input = document.getElementById('myInput');
    input.value = value;
    document.getElementById('myDropdown').classList.remove('show');
    eventNameSearch = document.getElementById('myInput').value;
    fetch(`editEvent.php?eventName=${encodeURIComponent(eventNameSearch)}`)
        .then(response => response.json())
        .then(eventDetails => {
            if (eventDetails.status === 'error') {
                alert(eventDetails.message);
            } else {
                defaultValues(eventDetails);
            }
        })
        .catch(error => console.error('Error fetching event details:', error));
}

function populateDropdown(elementId, start, end) {
    const dropdown = document.getElementById(elementId);
    for (let i = start; i <= end; i++) {
        const option = document.createElement('option');
        option.value = i < 10 ? '0' + i : i;
        option.textContent = i < 10 ? '0' + i : i;
        dropdown.appendChild(option);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('myInput').addEventListener('click', function(event) {
        event.stopPropagation();
    });

    document.getElementById('myDropdown').addEventListener('click', function(event) {
        event.stopPropagation();
    });

    document.addEventListener('click', function() {
        const dropdowns = document.getElementsByClassName('dropdown-content');
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    });
});

//update function gets values and updates event table upon Update Event button click
document.getElementById('updateEventButton').addEventListener('click', function() {
    console.log("yes");
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
    const origEventName = document.getElementById('myInput').value;

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
        eventName,
        origEventName
    };

    fetch('editEvent.php', {
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



