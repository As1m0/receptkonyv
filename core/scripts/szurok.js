

let szuro = document.getElementsByClassName("szuro-item");


for (let i = 0; i < szuro.length; i++) {
    szuro[i].addEventListener("change", () => {

        filters = {
            category: document.getElementById('category').value,
            ido: document.getElementById('ido').value,
            nehezseg: document.getElementById('nehezseg').value,
            ertekeles: document.getElementById('ertekeles').value
        };

        // AJAX kérés küldése a PHP backendnek
        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(filters),
        })
            .catch(error => console.error('Hiba:', error));

        console.log(JSON.stringify(filters));
    });
}