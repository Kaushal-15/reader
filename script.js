document.addEventListener("DOMContentLoaded", function () {
    const stickyNavbar = document.getElementById("sticky-navbar");
    const mainHeader = document.querySelector(".main-header");
    let lastScrollY = window.scrollY;

    window.addEventListener("scroll", function () {
        if (window.scrollY > mainHeader.offsetHeight) {
            stickyNavbar.classList.add("show");
        } else {
            stickyNavbar.classList.remove("show");
        }
        lastScrollY = window.scrollY;
    });
});
