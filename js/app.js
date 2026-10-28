// Algemene scripts voor elke pagina:
// 1. hamburgermenu op mobiel
// 2. schaduw onder de header bij scrollen
// 3. bestelknoppen: product toevoegen zonder de pagina te herladen
// 4. winkelwagen: aantal wijzigen = direct opslaan
// 5. kaarten rustig laten verschijnen bij scrollen

// ---------- 1. Hamburgermenu ----------
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

// ---------- 2. Header een schaduw geven als je naar beneden scrolt ----------
const header = document.querySelector('.site-header');

window.addEventListener('scroll', function () {
    header.classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

// ---------- 3. Bestelknoppen ----------
const toast = document.querySelector('[data-toast]');
let toastTimer;

function toonToast(tekst, metLink) {
    toast.innerHTML = '';
    const p = document.createElement('p');
    p.textContent = tekst;
    toast.appendChild(p);

    if (metLink) {
        const link = document.createElement('a');
        link.href = 'winkelwagen';
        link.className = 'btn btn-primary btn-small';
        link.textContent = 'Naar winkelwagen';
        toast.appendChild(link);
    }

    toast.hidden = false;
    // Even wachten zodat de animatie start
    requestAnimationFrame(function () {
        toast.classList.add('show');
    });

    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () {
        toast.classList.remove('show');
    }, 4500);
}

function updateTeller(aantal) {
    const teller = document.querySelector('[data-cart-count]');
    const link = document.querySelector('[data-cart-link]');
    teller.textContent = aantal;
    teller.hidden = aantal === 0;
    link.setAttribute('aria-label', 'Winkelwagen (' + aantal + ' artikelen)');

    // Klein "hupje" als er iets bij komt
    teller.classList.remove('bump');
    void teller.offsetWidth;
    teller.classList.add('bump');
}

document.querySelectorAll('[data-add-to-cart]').forEach(function (formulier) {
    formulier.addEventListener('submit', async function (event) {
        event.preventDefault();
        const knop = formulier.querySelector('button[type="submit"]');
        knop.disabled = true;
        knop.classList.add('loading');

        try {
            const antwoord = await fetch(formulier.action, {
                method: 'POST',
                body: new FormData(formulier),
                headers: { 'Accept': 'application/json' },
            });
            const gegevens = await antwoord.json();

            if (gegevens.ok) {
                updateTeller(gegevens.aantal);
                toonToast(gegevens.melding, true);
            } else {
                toonToast(gegevens.melding, false);
            }
        } catch (fout) {
            // Lukt het niet via JavaScript? Dan gewoon het formulier versturen.
            formulier.submit();
        } finally {
            knop.disabled = false;
            knop.classList.remove('loading');
        }
    });
});

// ---------- 4. Winkelwagen: aantal wijzigen = direct opslaan ----------
const winkelwagenFormulier = document.querySelector('[data-cart-form]');

if (winkelwagenFormulier) {
    // De knop "Aantallen bijwerken" is met JavaScript niet nodig
    winkelwagenFormulier.querySelectorAll('[data-hide-with-js]').forEach(function (knop) {
        knop.hidden = true;
    });

    winkelwagenFormulier.querySelectorAll('[data-auto-submit]').forEach(function (veld) {
        veld.addEventListener('change', function () {
            winkelwagenFormulier.submit();
        });
    });
}

// ---------- 5. Rustig verschijnen bij scrollen ----------
const verschijnen = document.querySelectorAll('.reveal');

// (De class "js" op <html> wordt al in de <head> gezet, zie header.php, zodat er niks knippert.)
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(function (items) {
        items.forEach(function (item) {
            if (item.isIntersecting) {
                item.target.classList.add('visible');
                observer.unobserve(item.target);
            }
        });
    }, { rootMargin: '0px 0px -40px 0px' });

    verschijnen.forEach(function (element) {
        observer.observe(element);
    });
} else {
    verschijnen.forEach(function (element) {
        element.classList.add('visible');
    });
}

// Meldingen bovenaan de pagina na een paar seconden laten verdwijnen
document.querySelectorAll('.alert-flash').forEach(function (melding) {
    setTimeout(function () {
        melding.classList.add('hide');
    }, 5000);
});
