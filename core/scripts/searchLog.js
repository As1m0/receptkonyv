function findMostCommonElement() {
    const table = document.getElementById('search-table');
    const rows = table.querySelectorAll('tbody tr');


    const frequencyMap = new Map();

    rows.forEach(row => {
        const searchTerm = row.children[1].textContent.trim(); // Get the text from the second column
        frequencyMap.set(searchTerm, (frequencyMap.get(searchTerm) || 0) + 1);
    });

    let mostCommon = null;
    let maxCount = 0;

    frequencyMap.forEach((count, term) => {
        if (count > maxCount) {
            mostCommon = term;
            maxCount = count;
        }
    });

    document.getElementById("result").innerHTML = `A leggyakrabban keresett kifejezés: "${mostCommon}" ${maxCount} kereséssel.`;
    return mostCommon;
}

findMostCommonElement();