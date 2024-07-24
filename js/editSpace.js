document.addEventListener('DOMContentLoaded', function() {
    const AddOrRemove = document.getElementById('AddOrRemove');
    const BuildingOrRoom = document.getElementById('BuildingOrRoom');
    const buildingDropdownDiv = document.getElementById('buildingDropdownDiv');
    const buildingDropdown = document.getElementById('buildingDropdown');
    const roomDropdownDiv = document.getElementById('roomDropdownDiv');
    const roomDropdown = document.getElementById('roomDropdown');
    const roomNameInsertDiv = document.getElementById('roomNameDiv');
    const roomNameInsert = document.getElementById('roomName');
    const editSpaceElemsDiv = document.getElementById('editSpaceElems');

    BuildingOrRoom.addEventListener('change', function() {
        editSpaceElemsDiv.style.display = 'flex';
        if (AddOrRemove.value === "add" && BuildingOrRoom.value === "building") {
            roomNameInsertDiv.style.display = 'inline';
        } else if (AddOrRemove.value === "add" && BuildingOrRoom.value === "room") {
            fetchBuildings();
            roomNameInsertDiv.style.display = 'inline';
        } else if (AddOrRemove.value === "remove" && BuildingOrRoom.value === "building") {
            fetchBuildings();
        } else if (AddOrRemove.value === "remove" && BuildingOrRoom.value === "room") {
            fetchBuildings();
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

            // Add change event listener to buildingDropdown
            buildingDropdown.addEventListener('change', function() {
                console.log(buildingDropdown.value);
                if (buildingDropdown.value) {
                    fetch('editSpace.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `BuildingOrRoom=room&building_name=${buildingDropdown.value}`
                    })
                    .then(response => {
                        console.log('Full response:', response);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Parsed data:', data);
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
            });
        })
        .catch(error => console.error('Error fetching buildings:', error));
    }
});
