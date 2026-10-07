// Inschrijfformulier masterclass:
// 1. live samenvatting met totaalprijs
// 2. tekenteller bij opmerkingen
// 3. controle in de browser voordat het formulier verstuurd wordt
//    (PHP controleert daarna nóg een keer, want JavaScript kan je uitzetten)

const formulier = document.querySelector('#inschrijfformulier');

if (formulier) {
    const classVeld = document.querySelector('#masterclass');
    const personenVeld = document.querySelector('#personen');
    const wensenVeld = document.querySelector('#wensen');

    // ---------- 1. Samenvatting ----------
    function updateSamenvatting() {
        const gekozen = classVeld.options[classVeld.selectedIndex];
        const personen = Number(personenVeld.value);

        document.querySelector('#sum-personen').textContent = personen;

        if (classVeld.value === '') {
            document.querySelector('#sum-class').textContent = 'Nog niet gekozen';
            document.querySelector('#sum-datum').textContent = '–';
            document.querySelector('#sum-totaal').textContent = '€ 0,-';
            document.querySelector('#sum-plekken').textContent = '';
            return;
        }

        const prijs = Number(gekozen.dataset.prijs);
        const plekken = Number(gekozen.dataset.plekken);
        const totaal = prijs * personen;

        // Alleen de titel tonen (tekst vóór het streepje)
        document.querySelector('#sum-class').textContent = gekozen.textContent.split('–')[0].trim();
        document.querySelector('#sum-datum').textContent = gekozen.dataset.datum;
        document.querySelector('#sum-totaal').textContent = '€ ' + totaal.toLocaleString('nl-NL') + ',-';
        document.querySelector('#sum-plekken').textContent = 'Nog ' + plekken + ' plekken beschikbaar';
    }

    classVeld.addEventListener('change', updateSamenvatting);
    personenVeld.addEventListener('change', updateSamenvatting);
    updateSamenvatting();

    // ---------- 2. Tekenteller ----------
    const teller = document.querySelector('#tekens');

    function updateTeller() {
        teller.textContent = wensenVeld.value.length;
    }

    wensenVeld.addEventListener('input', updateTeller);
    updateTeller();

    // ---------- 3. Controle voor verzenden ----------
    function toonFout(veld, melding) {
        const blok = veld.closest('.field');
        blok.classList.add('has-error');

        let p = blok.querySelector('.field-error');
        if (!p) {
            p = document.createElement('p');
            p.className = 'field-error';
            blok.appendChild(p);
        }
        p.textContent = melding;
    }

    function wisFout(veld) {
        const blok = veld.closest('.field');
        blok.classList.remove('has-error');
        const p = blok.querySelector('.field-error');
        if (p) {
            p.remove();
        }
    }

    formulier.addEventListener('submit', function (event) {
        let fouten = 0;

        const voornaam = document.querySelector('#voornaam');
        const achternaam = document.querySelector('#achternaam');
        const email = document.querySelector('#email');
        const telefoon = document.querySelector('#telefoon');
        const ervaring = document.querySelector('input[name="ervaring"]:checked');
        const akkoord = document.querySelector('input[name="akkoord"]');

        // Eerst alle oude fouten weghalen
        formulier.querySelectorAll('input, select, textarea').forEach(function (veld) {
            if (veld.closest('.field')) {
                wisFout(veld);
            }
        });

        if (classVeld.value === '') {
            toonFout(classVeld, 'Kies een masterclass.');
            fouten++;
        }
        if (voornaam.value.trim() === '') {
            toonFout(voornaam, 'Vul je voornaam in.');
            fouten++;
        }
        if (achternaam.value.trim() === '') {
            toonFout(achternaam, 'Vul je achternaam in.');
            fouten++;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            toonFout(email, 'Vul een geldig e-mailadres in.');
            fouten++;
        }
        if (telefoon.value.trim() !== '' && !/^[0-9 +()-]{10,15}$/.test(telefoon.value.trim())) {
            toonFout(telefoon, 'Vul een geldig telefoonnummer in (of laat het leeg).');
            fouten++;
        }
        if (classVeld.value !== '') {
            const plekken = Number(classVeld.options[classVeld.selectedIndex].dataset.plekken);
            if (Number(personenVeld.value) > plekken) {
                toonFout(personenVeld, 'Er zijn niet genoeg plekken meer voor dit aantal personen.');
                fouten++;
            }
        }
        if (!ervaring) {
            toonFout(document.querySelector('input[name="ervaring"]'), 'Geef aan hoeveel ervaring je hebt.');
            fouten++;
        }
        if (!akkoord.checked) {
            toonFout(akkoord, 'Je moet akkoord gaan om je in te schrijven.');
            fouten++;
        }

        // Fouten? Dan niet versturen en naar het eerste foute veld scrollen
        if (fouten > 0) {
            event.preventDefault();
            const eerste = formulier.querySelector('.has-error');
            eerste.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const invoer = eerste.querySelector('input, select, textarea');
            if (invoer) {
                invoer.focus({ preventScroll: true });
            }
        }
    });
}
