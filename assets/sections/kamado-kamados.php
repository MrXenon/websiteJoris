<?php
/**
 * Kamado Breda – kamado's: de kamado's van de webshop, met de kaarten van alle kamado's.
 * Hier staan de producten zelf: naam, prijs, foto's, specificaties. De productpagina's (/kamados/{id}),
 * de winkelwagen en de uitgelichte kamado's op de homepage gebruiken deze gegevens.
 * Gebruik deze layout één keer (op de pagina kamados).
 * Subtitle = klein label boven de titel. Title = titel. Content = intro.
 * Preview: card-grid
 * Setting: producten | list | Kamado's
 * Setting: producten[].id | text | ID | Staat in de link: /kamados/{id}. Alleen kleine letters, cijfers en -, bijv. "blue-l". Niet meer wijzigen als er al bestellingen zijn.
 * Setting: producten[].naam | text | Naam
 * Setting: producten[].kort | text | Korte omschrijving
 * Setting: producten[].prijs | number | Prijs (incl. btw)
 * Setting: producten[].badge | text | Label op de foto | Bijv. "Bestseller"
 * Setting: producten[].foto | image | Hoofdfoto
 * Setting: producten[].tekst | textarea | Tekst op de productpagina
 * Setting: producten[].kenmerken | textarea | Kenmerken | Eén per regel
 * Setting: producten[].specs | textarea | Specificaties | Eén per regel: naam | waarde, bijv. "Gewicht | 96 kg"
 * Setting: producten[].galerij | textarea | Foto's op de productpagina | Eén per regel: bestand | omschrijving. Leeg = alleen de hoofdfoto.
 * Setting: voordelen | textarea | Voordelen in het bestelblok | Eén per regel, getoond op elke productpagina
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
?>
<section class="<?= cmsSectieKlasse($fpc_obj) ?>"<?= cms_anchor($fpc_obj) ?>>
    <div class="container">
        <?php include __DIR__ . '/../../includes/sectie-kop.php'; ?>
        <div class="grid grid-3">
            <?php foreach ($kamados as $id => $kamado) {
                include __DIR__ . '/../../includes/kamado-kaart.php';
            } ?>
        </div>
    </div>
</section>
