const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const rating = star.getAttribute('data-value');
        ratingInput.value = rating;

        stars.forEach(s => s.classList.remove('selected'));
        for (let i = 0; i < rating; i++) {
            stars[i].classList.add('selected');
        }

        document.getElementById("submit-button").classList.remove("disabled");
    });
});

document.getElementById("review-area").addEventListener("change", () => {
    /*
    //Kötelező review írás esetén:
    if (document.getElementById("review-area").value.length > 4) {
        document.getElementById("submit-button").classList.remove("disabled");
    } else{
        document.getElementById("submit-button").classList.add("disabled");  
    }
     */
});
