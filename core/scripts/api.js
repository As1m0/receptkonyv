let lastChecked = new Date(new Date(new Date().toLocaleString("en-US", { timeZone: "Europe/Budapest" })).getTime() + 3600000).toISOString();

async function checkForNewRecipe() {

    try {
        const response = await fetch(`https://www.receptkonyved.hu/api/APIHandler.php/check-new-recipe?lastChecked=${encodeURIComponent(lastChecked)}`);
        if (!response.ok) {
            throw new Error('Failed to fetch API');
        }
        const data = await response.json();

        if (data.status === 'success') {

            if (Object.hasOwn(data, 'RecepieName') && Object.hasOwn(data, 'RecepieID')) {
                const recepieName = data.RecepieName;
                const recepieID = data.RecepieID;
                popUpWindow(recepieName, recepieID);
            }

        } else {
            console.error('Error from API:', data.message);
        }
    } catch (error) {
        console.error('Error checking for new recipes:', error);
    }
    lastChecked = new Date(new Date(new Date().toLocaleString("en-US", { timeZone: "Europe/Budapest" })).getTime() + 3600000).toISOString();
}

checkForNewRecipe();
setInterval(checkForNewRecipe, 30000); //30 mp

let popUpBlock = document.getElementById("pop-up");
let receptLink = document.getElementById("pop-up-link");
let NameText = document.getElementById("recepie-name");
let closeBtn = document.getElementById("pop-cls-btn");

closeBtn.addEventListener("click", closePopUp);

function popUpWindow(recepieName, recepieID) {
    NameText.textContent = recepieName;
    receptLink.setAttribute("href", `index.php?p=recept-aloldal&r=${recepieID}`);
    popUpBlock.style.display = "block";
    popUpBlock.classList.add("slide-in");
    setTimeout(closePopUp, 7000);
}

function closePopUp() {
    popUpBlock.classList.remove("slide-in");
    popUpBlock.classList.add("fade-out");
    setTimeout(() => {
        popUpBlock.style.display = "none";
        popUpBlock.classList.remove("fade-out");
    }, 500);
}