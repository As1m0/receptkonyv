let selectElements = document.getElementsByTagName("select");

for(let i=0; i<selectElements.length; i++){
    selectElements[i].addEventListener("change", () => {
        console.log(selectElements[i].value);
        // how to post to php on change
        fetch('ReceptekPage.php', {
            method: 'POST',
            body: selectElements[i].value,
        })
    })
}
