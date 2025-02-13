function removeFilter(inputId) {
    const inputElement = document.getElementById(inputId);
    if (inputElement) {
        inputElement.value = '';
        const form = document.querySelector('#szurok');
        if (form) form.submit();
    }
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
        if (selectElement && removeElement) {
            if (selectElement.value.trim() !== '') {
                removeElement.classList.remove('d-none');
            } else {
                removeElement.classList.add('d-none');
            }
        }
    });
}

filterElements.forEach(({ select }) => {
    const selectElement = document.getElementById(select);
    if (selectElement) {
        selectElement.addEventListener('change', CheckFilters);
    }
});

CheckFilters();



