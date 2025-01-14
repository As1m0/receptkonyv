document.getElementById("password-check").addEventListener("change", () => {
    if (document.getElementById("password-check").value !== document.getElementById("password").value) {
        document.getElementById("password-check").value = "";
        document.getElementById("password-check").style.backgroundColor = "rgba(255,0,0,.2)";
        alert("A két jelszó nem egyezik meg!");
    }
    else {
        document.getElementById("password").style.backgroundColor = "rgba(0,255,0,.1)";
        document.getElementById("password-check").style.backgroundColor = "rgba(0,255,0,.1)";
    }
});