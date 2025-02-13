function removeFilter(inputId) {
    document.getElementById(inputId).value = '';
    document.querySelector('#szurok').submit();
}

const filterElements = [
    { select: 'category', remove: 'remove-category' },
    { select: 'time', remove: 'remove-time' },
    { select: 'nehezseg', remove: 'remove-diff' },
    { select: 'review', remove: 'remove-review' }
];

function CheckFilters() {
    filterElements.forEach(({ select, remove }) => {
        const selectElement = document.getElementById(select);
        const removeElement = document.getElementById(remove);
        if (selectElement.value.trim() !== '') {
            removeElement.classList.remove('d-none');
        } else {
            removeElement.classList.add('d-none');
        }
    });
}


filterElements.forEach(({ select }) => {
    document.getElementById(select).addEventListener('change', CheckFilters);
});


CheckFilters();
