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

function selectEvent(value) {
    const input = document.getElementById('myInput');
    input.value = value;
    document.getElementById('myDropdown').classList.remove('show');
}

document.addEventListener('click', function(event) {
    if (!event.target.matches('#myInput')) {
        const dropdowns = document.getElementsByClassName('dropdown-content');
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
});

