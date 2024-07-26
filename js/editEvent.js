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

function defaultValues(eventDetails) {
    populateDropdown('startHour', 1, 12);
    populateDropdown('endHour', 1, 12);
    populateDropdown('startMin', 0, 59);
    populateDropdown('endMin', 0, 59);

    const elementsToDisplay = [
        'setEventName', 'startDateDiv', 'endDateDiv', 'startTime', 
        'endTime', 'selectBuilding', 'selectRoom'
    ];
    elementsToDisplay.forEach(id => {
        const element = document.getElementById(id);
        element.style.display = 'block';
    });
    console.log(eventDetails);
    //fill in default values
    // start time, end time, building, and room need proper selections
    const startHour = eventDetails.startTime.substring(0, 2);
    const startMin = eventDetails.startTime.substring(3, 5);
    const startMer = eventDetails.startTime.substring(6, 8);
    const endHour = eventDetails.endTime.substring(0, 2);
    const endMin = eventDetails.endTime.substring(3, 5);
    const endMer = eventDetails.endTime.substring(6, 8);

    console.log(eventDetails.building);

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
    document.getElementById('roomSelect').value = eventDetails.room;

    //update function gets values and updates event table upon Update Event button click
}

function selectEvent(value) {
    const input = document.getElementById('myInput');
    input.value = value;
    document.getElementById('myDropdown').classList.remove('show');
    eventNameSearch = document.getElementById('myInput').value;
    console.log("event name" + eventNameSearch);
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

function populateDropdown(elementId, start, end) {
    const dropdown = document.getElementById(elementId);
    for (let i = start; i <= end; i++) {
        const option = document.createElement('option');
        option.value = i < 10 ? '0' + i : i;
        option.textContent = i < 10 ? '0' + i : i;
        console.log(option)
        dropdown.appendChild(option);
    }
}

