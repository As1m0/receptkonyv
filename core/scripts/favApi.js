function saveToFavorites(itemId) {
    fetch('api/save_favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ item_id: itemId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                //console.log(data.result);
                //console.log(data.ids);
                if (data.result === "inserted") {
                    document.getElementById("icon-" + itemId).setAttribute("src", "content/heart_icons/heart2.png");
                }
                else if (data.result === "removed") {
                    document.getElementById("icon-" + itemId).setAttribute("src", "content/heart_icons/heart1.png");
                }
            } else {
                console.log('Hiba a kedvenchez adás közben: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
}