

let lastChecked = new Date().toISOString();
let lastRecipeName = null; 

async function checkForNewRecipe() {
    try {
        const response = await fetch(`/Receptkonyv_CMS/api/APIHandler.php/check-new-recipe?lastChecked=${encodeURIComponent(lastChecked)}`);
        if (!response.ok) {
            throw new Error('Failed to fetch API');
        }
        const data = await response.json();

        if (data.status === 'success') {
            const latestRecipe = data.newRecipe;

            // Check if the recipe is new
            if (lastRecipeName !== latestRecipe.recept_nev && latestRecipe !== "" ) {
                console.log(`New recipe uploaded: ${latestRecipe}`);

                //call function - pop-up-window


                lastRecipeName = latestRecipe.recept_nev; 
                lastChecked = new Date().toISOString();
            } else {
                //console.log('No new recipes.');
                return;
            }
        } else {
            console.error('Error from API:', data.message);
        }
    } catch (error) {
        console.error('Error checking for new recipes:', error);
    }
}

// Poll the server every 10 seconds
setInterval(checkForNewRecipe, 3000);







  