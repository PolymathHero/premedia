document.addEventListener("DOMContentLoaded", function() {
    const closeOverlay = document.querySelector('.guest-pop-up-close-overlay-div');

    closeOverlay.addEventListener('click', function() {
        document.documentElement.classList.remove('on-guest-pic-click');
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const guestImgs = document.querySelectorAll('.guest-img');

    guestImgs.forEach(function(guestImg) {
        guestImg.addEventListener('click', function() {
            document.documentElement.classList.add('on-guest-pic-click');
        });
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const guestImgs = document.querySelectorAll('.guest-img');
    const guestPopUpImg = document.querySelector('.guest-pop-up-image-container-div img');

    guestImgs.forEach(function(guestImg) {
        guestImg.addEventListener('click', function() {
            const imgSrc = this.src.split('/').pop(); // Get the image file name from the src
            const newSrc = `/images/${imgSrc.replace('.jpg', '-ig.jpg')}`; // Replace .jpg with -ig.jpg

            guestPopUpImg.src = newSrc; // Update the src attribute of the guest pop-up image
        });
    });
});
