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

let cameraIcon = document.getElementById("profil-pic-icon");
cameraIcon.addEventListener("click", ()=>{
    document.getElementById("img-form").classList.remove("d-none");
    document.getElementById("main-content").classList.add("blur");
});

let closeBtn = document.getElementById("close-btn");
closeBtn.addEventListener("click", () => {
    document.getElementById("img-form").classList.add("d-none");
    document.getElementById("main-content").classList.remove("blur");
});

let PicInput = document.getElementById("picture-input");
PicInput.addEventListener("change", (event) => {
    let file = event.target.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById("update-img-prev").setAttribute("src", e.target.result);
        };
        reader.readAsDataURL(file);
    }
});
