document.addEventListener('DOMContentLoaded', function () {
    var cookiePopup = document.getElementById('cookie-popup');
    var acceptBtn = document.getElementById('accept-btn');

    if (document.cookie.indexOf('cookieAccepted=true') === -1) {
        setTimeout(function () {
            cookiePopup.style.display = 'block';
        }, 1300);
    }

    acceptBtn.addEventListener('click', function () {
        cookiePopup.style.display = 'none';
        document.cookie = "cookieAccepted=true; path=/; max-age=" + 60 * 60 * 24 * 365;
    });
});