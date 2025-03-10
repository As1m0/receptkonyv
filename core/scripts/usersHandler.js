let submitButtons = document.getElementsByClassName("submit-btn");

    for (let i = 0; i < submitButtons.length; i++) {
        submitButtons[i].addEventListener("click", (event) => {
            event.preventDefault();
            const userResponse = confirm("Biztosan törölni akarod ezt a felhasználót? (minden hozzá tartozó komment és recept is törlődni fog!!!)");
            if (userResponse) {
                const form = event.target.closest("form");
                if (form) {
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "delete";
                    hiddenInput.value = "ok";
                    form.appendChild(hiddenInput);
                    form.submit();
                } else {
                    console.error("No form found for the button!");
                }
            }
        });
    }


    const editBtn = document.getElementsByClassName("edit-btn");
    const editModal = document.getElementById("editModal");
    const editForm = document.getElementById("editForm");
    const userNameInput = document.getElementById("userName");
    const userEmailInput = document.getElementById("userEmail");
    const groupMember = document.getElementById("groupMem");
    const editUserIdInput = document.getElementById("editUserId");

    // Open the editor
    for (let i = 0; i < editBtn.length; i++) {
        editBtn[i].addEventListener("click", (event) => {

            const row = event.target.closest("tr");
            const userId = row.cells[0].textContent;
            const userName = row.cells[1].textContent;
            const userEmail = row.cells[2].textContent;
            const groupMem = row.cells[3].textContent;

            editUserIdInput.value = userId;
            userNameInput.value = userName;
            userEmailInput.value = userEmail;
            groupMember.value = groupMem;

            editModal.style.display = "block";

        });
    }

    //hide editor
    document.getElementById("cancelEdit").addEventListener("click", () => {
        editModal.style.display = "none";
    });


    //save changes
    document.getElementById("saveChanges").addEventListener("click", () => {

        const hiddenInput0 = document.createElement("input");
        hiddenInput0.type = "hidden";
        hiddenInput0.name = "userID";
        hiddenInput0.value = editUserIdInput.value;
        editForm.appendChild(hiddenInput0);

        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "mail";
        hiddenInput.value = userEmailInput.value;
        editForm.appendChild(hiddenInput);

        const hiddenInput1 = document.createElement("input");
        hiddenInput1.type = "hidden";
        hiddenInput1.name = "groupMember";
        hiddenInput1.value = groupMember.value;
        editForm.appendChild(hiddenInput1);

        const hiddenInput2 = document.createElement("input");
        hiddenInput2.type = "hidden";
        hiddenInput2.name = "name";
        hiddenInput2.value = userNameInput.value;
        editForm.appendChild(hiddenInput2);

        editForm.submit();
    });