function deleteRecepie(receptId) {
    const userResponse = confirm("Biztosan törölni akarod ezt a receptet?");
    if (userResponse) {
        const form = event.target.closest("form");
            if (form) {
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "delete-recepie";
                hiddenInput.value = receptId;
                form.appendChild(hiddenInput);
                form.submit();
            } else {
                console.error("No form found for the button!");
            }
    }
}

function deleteUser(userID)
{
    const userResponse = confirm("Biztosan törölni akarod a profilodat? (Minden hozzátartozó recept és hozzászólás is törlődni fog!)");
    if (userResponse) {
        const form = event.target.closest("form");
            if (form) {
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "delete-user";
                hiddenInput.value = userID;
                form.appendChild(hiddenInput);
                form.submit();
            } else {
                console.error("No form found for the button!");
            }
    }
}