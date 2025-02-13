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


let RemoveSearchElement = document.getElementById("remove-search");
let SearchInputElement = document.getElementById("search");
let SearchForm = document.getElementById("text-search-form");

function removeSearchBarValue() {
    SearchInputElement.value = "";
    SearchInputElement.dispatchEvent(new Event("input")); // Manually trigger input event to update UI
    SearchInputElement.setAttribute("placeholder", "Keresés...");
    RemoveSearchElement.classList.add("d-none");
    //setTimeout(() => { SearchForm.submit(); }, 0);
}

function checkInputBox() {
    if (SearchInputElement.value.trim() === "") {
        RemoveSearchElement.classList.add("d-none");
    } else {
        RemoveSearchElement.classList.remove("d-none");
    }
}

SearchInputElement.addEventListener("input", checkInputBox);
RemoveSearchElement.addEventListener("click", removeSearchBarValue);

// Initial check
checkInputBox();
