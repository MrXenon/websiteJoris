// Afrekenformulier:
// 1. adresvelden alleen tonen bij "bezorgen"
// 2. "betalen bij afhalen" alleen mogelijk bij afhalen
// 3. verzendkosten en totaal direct aanpassen
// (PHP controleert alles daarna nóg een keer, want JavaScript kan je uitzetten)

const afrekenFormulier = document.querySelector('#afrekenformulier');

if (afrekenFormulier) {
    const adres = afrekenFormulier.querySelector('[data-adres]');
    const betaalAfhalen = afrekenFormulier.querySelector('[data-betaal-afhalen]');
    const knopOnline = afrekenFormulier.querySelector('[data-knop-online]');
    const knopAfhalen = afrekenFormulier.querySelector('[data-knop-afhalen]');
    const bedragen = document.querySelectorAll('[data-verzendkosten], [data-totaal], [data-btw]');

    function update() {
        const levering = afrekenFormulier.querySelector('input[name="levering"]:checked').value;
        const bezorgen = levering === 'bezorgen';

        adres.hidden = !bezorgen;
        betaalAfhalen.hidden = bezorgen;

        // Bezorgen gekozen terwijl "betalen bij afhalen" aan stond? Dan terug naar online betalen.
        if (bezorgen) {
            afrekenFormulier.querySelector('input[name="betaalwijze"][value="online"]').checked = true;
        }

        const betaalwijze = afrekenFormulier.querySelector('input[name="betaalwijze"]:checked').value;
        knopOnline.hidden = betaalwijze === 'afhalen';
        knopAfhalen.hidden = betaalwijze !== 'afhalen';

        // De bedragen voor beide keuzes staan al klaar in data-bezorgen / data-afhalen
        bedragen.forEach(function (element) {
            element.textContent = element.dataset[levering];
        });
    }

    afrekenFormulier.querySelectorAll('input[name="levering"], input[name="betaalwijze"]').forEach(function (veld) {
        veld.addEventListener('change', update);
    });

    // Postcode netjes maken: "4811ab" -> "4811 AB"
    const postcode = afrekenFormulier.querySelector('#postcode');
    postcode.addEventListener('blur', function () {
        const schoon = postcode.value.replace(/\s/g, '').toUpperCase();
        if (/^[1-9][0-9]{3}[A-Z]{2}$/.test(schoon)) {
            postcode.value = schoon.slice(0, 4) + ' ' + schoon.slice(4);
        }
    });

    // Eerste veld met een fout in beeld brengen
    const eersteFout = afrekenFormulier.querySelector('.has-error input, .has-error textarea');
    if (eersteFout) {
        eersteFout.focus();
    }

    // Dubbel klikken voorkomen (anders twee bestellingen)
    afrekenFormulier.addEventListener('submit', function () {
        afrekenFormulier.querySelector('button[type="submit"]').classList.add('loading');
    });
}
