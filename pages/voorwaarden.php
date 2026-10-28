<?php
defined('KAMADO_BREDA') || exit;

$paginaTitel = 'Bezorgen, retourneren & voorwaarden';
$beschrijving = 'Informatie over bezorgen, afhalen, betalen en retourneren bij de webshop van Kamado Breda.';
require __DIR__ . '/../includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Webshop</p>
        <h1>Bezorgen, retourneren &amp; voorwaarden</h1>
    </div>
</section>

<section class="section">
    <div class="container narrow prose">
        <!-- CONCEPT: laat deze tekst controleren voordat de webshop live gaat. -->

        <h2>Bezorgen en afhalen</h2>
        <p>Kamado's bezorgen we zelf in de regio Breda. Na je bestelling bellen we je binnen 2 werkdagen om een afspraak te maken.
           Accessoires versturen we door heel Nederland voor <?= prijs($config['verzendkosten']) ?>; vanaf <?= prijs($config['gratis_verzending_vanaf']) ?> is bezorgen gratis.</p>
        <p>Liever ophalen? Dat is altijd gratis. Je bestelling ligt vanaf de volgende werkdag klaar in de winkel aan de Voorbeeldstraat 12 in Breda.</p>

        <h2>Betalen</h2>
        <p>Je betaalt veilig online via Mollie met iDEAL, Bancontact of creditcard. Haal je je bestelling op in de winkel,
           dan kun je ook daar betalen met pin of contant. Alle prijzen zijn inclusief 21% btw.</p>

        <h2>Herroepingsrecht en retourneren</h2>
        <p>Je hebt 14 dagen bedenktijd vanaf het moment dat je je bestelling hebt ontvangen. Wil je iets terugsturen of terugbrengen?
           Mail ons via <a href="mailto:info@kamadobreda.nl">info@kamadobreda.nl</a>. We betalen het aankoopbedrag binnen 14 dagen terug.
           Het product moet ongebruikt zijn en in de originele verpakking zitten.</p>

        <h2>Garantie</h2>
        <p>Op het keramiek van de Blue M en Blue L zit 10 jaar garantie, op de Blue XL levenslang. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

        <h2>Privacy</h2>
        <p>We gebruiken je gegevens alleen om je bestelling te verwerken en te bezorgen. We delen ze niet met anderen,
           behalve met onze betaalprovider om je betaling af te handelen.</p>

        <h2>Contact</h2>
        <p>Kamado Breda &middot; Voorbeeldstraat 12, 4811 AB Breda &middot; <a href="tel:+31760000000">076 – 000 00 00</a> &middot;
           <a href="mailto:info@kamadobreda.nl">info@kamadobreda.nl</a></p>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
