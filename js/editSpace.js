document.addEventListener('DOMContentLoaded', function() {
    const AddOrRemove = document.getElementById('AddOrRemove');
    const BuildingOrRoom = document.getElementById('BuildingOrRoom');
    const buildingDropdownDiv = document.getElementById('buildingDropdownDiv');
    const buildingDropdown = document.getElementById('buildingDropdown');
    const roomDropdownDiv = document.getElementById('roomDropdownDiv');
    const roomDropdown = document.getElementById('roomDropdown');
    const roomNameInsertDiv = document.getElementById('roomNameDiv');
    const editSpaceElemsDiv = document.getElementById('editSpaceElems');
    const editSpaceForm = document.getElementById('editSpaceForm');

    BuildingOrRoom.addEventListener('change', function() {
        editSpaceElemsDiv.style.display = 'flex';
        if (AddOrRemove.value === "add" && BuildingOrRoom.value === "building") {
            roomNameInsertDiv.style.display = 'inline';
            buildingDropdownDiv.style.display = 'none';
            roomDropdownDiv.style.display = 'none';
        } else if (AddOrRemove.value === "add" && BuildingOrRoom.value === "room") {
            fetchBuildings();
            roomNameInsertDiv.style.display = 'inline';
            roomDropdownDiv.style.display = 'none';
        } else if (AddOrRemove.value === "remove" && BuildingOrRoom.value === "building") {
            fetchBuildings();
            roomDropdownDiv.style.display = 'none';
            roomNameInsertDiv.style.display = 'none';
        } else if (AddOrRemove.value === "remove" && BuildingOrRoom.value === "room") {
            fetchBuildings();
            roomNameInsertDiv.style.display = 'none';
        }
    });

    function fetchBuildings() {
        fetch('editSpace.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'BuildingOrRoom=building'
        })
        .then(response => response.json())
        .then(data => {
            if (!buildingDropdown) {
                console.error('buildingDropdown element is missing');
                return;
            }
            console.log('Buildings fetched:', data);
            buildingDropdown.innerHTML = '<option value="" disabled selected>Select Building</option>';
            data.forEach(building => {
                const option = document.createElement('option');
                option.value = building;
                option.textContent = building;
                buildingDropdown.appendChild(option);
            });
            buildingDropdownDiv.style.display = 'inline';

            buildingDropdown.addEventListener('change', function() {
                console.log(buildingDropdown.value);
                if (AddOrRemove.value === "remove" && BuildingOrRoom.value === "room") {
                    fetchRooms(buildingDropdown.value);
                }
            });
        })
        .catch(error => console.error('Error fetching buildings:', error));
    }

    function fetchRooms(buildingName) {
        fetch('editSpace.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `BuildingOrRoom=room&building_name=${buildingName}`
        })
        .then(response => response.json())
        .then(data => {
            if (!roomDropdown) {
                console.error('roomDropdown element is missing');
                return;
            }
            console.log('Rooms fetched:', data);
            roomDropdown.innerHTML = '<option value="" disabled selected>Select Room</option>';
            data.forEach(room => {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomDropdown.appendChild(option);
            });
            roomDropdownDiv.style.display = 'inline';
        })
        .catch(error => console.error('Error fetching rooms:', error));
    }

    editSpaceForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const addOrRemove = AddOrRemove.value;
        const buildingOrRoom = BuildingOrRoom.value;
        let buildingName = '';
        let roomName = '';

        if (addOrRemove === 'add' && buildingOrRoom === 'building') {
            buildingName = document.getElementById('roomName').value;
        } else if ((addOrRemove === 'add' && buildingOrRoom === 'room') || (addOrRemove === 'remove' && buildingOrRoom === 'room')) {
            buildingName = document.getElementById('buildingDropdown').value;
            if (addOrRemove === 'add') {
                roomName = document.getElementById('roomName').value;
            } else {
                roomName = document.getElementById('roomDropdown').value;
            }
        } else if (addOrRemove === 'remove' && buildingOrRoom === 'building') {
            buildingName = document.getElementById('buildingDropdown').value;
        }

        console.log("add or remove " + addOrRemove);
        console.log("building or room " + buildingOrRoom);
        console.log("building name " + buildingName);
        console.log("room name " + roomName);

        const formData = new FormData();
        formData.append('AddOrRemove', addOrRemove);
        formData.append('BuildingOrRoom', buildingOrRoom);
        formData.append('building_name', buildingName);
        formData.append('room_name', roomName);

        for (let entry of formData) {
            console.log(`Field: ${entry[0]}, Value: ${entry[1]}`);
          }
          
        fetch('editSpace.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            alert(data);
        })
        .catch(error => console.error('Error:', error));
    });
});


