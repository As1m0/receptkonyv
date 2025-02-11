
function removeFilter(input) {
    let form = document.querySelector('#szurok');
    let inputElement = document.getElementById(input);
    inputElement.value = '';
    form.submit();
}

let selectCategoryElement = document.getElementById('category');
let CategoryXElement = document.getElementById('remove-category');
selectCategoryElement.addEventListener('change', () => {
    CheckFilters();
});

let selectTimeElement = document.getElementById('time');
let TimeXElement = document.getElementById('remove-time');
selectTimeElement.addEventListener('change', () => {
    CheckFilters();
});

let selectDiffElement = document.getElementById('nehezseg');
let DiffXElement = document.getElementById('remove-diff');
selectDiffElement.addEventListener('change', () => {
    CheckFilters();
});

let selectReviewElement = document.getElementById('review');
let ReviewXElement = document.getElementById('remove-review');
selectReviewElement.addEventListener('change', () => {
    CheckFilters();
});

CheckFilters();

function CheckFilters() {
    if (selectCategoryElement.value != '') {
        CategoryXElement.classList.remove('d-none');
    } else {
        CategoryXElement.classList.add('d-none');
    }
    if (selectTimeElement.value != '') {
        TimeXElement.classList.remove('d-none');
    } else {
        TimeXElement.classList.add('d-none');
    }
    if (selectDiffElement.value != '') {
        DiffXElement.classList.remove('d-none');
    } else {
        DiffXElement.classList.add('d-none');
    }
    if (selectReviewElement.value != '') {
        ReviewXElement.classList.remove('d-none');
    } else {
        ReviewXElement.classList.add('d-none');
    }

}