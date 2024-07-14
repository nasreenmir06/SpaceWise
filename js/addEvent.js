document.addEventListener('DOMContentLoaded', function() {
    populateDropdown('startHour', 1, 12);
    populateDropdown('endHour', 1, 12);
    populateDropdown('startMin', 0, 59);
    populateDropdown('endMin', 0, 59);
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
