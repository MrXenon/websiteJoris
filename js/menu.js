// Hamburgermenu voor mobiel
const menuKnop = document.querySelector('.menu-toggle');
const menu = document.querySelector('.main-nav');

if (menuKnop && menu) {
    menuKnop.addEventListener('click', function () {
        const isOpen = menu.classList.toggle('open');
        menuKnop.classList.toggle('open', isOpen);
        menuKnop.setAttribute('aria-expanded', isOpen);
        menuKnop.setAttribute('aria-label', isOpen ? 'Menu sluiten' : 'Menu openen');
    });
}

// Header een schaduw geven als je naar beneden scrolt
const header = document.querySelector('.site-header');

window.addEventListener('scroll', function () {
    header.classList.toggle('scrolled', window.scrollY > 10);
});
