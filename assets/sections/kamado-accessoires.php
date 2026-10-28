<?php
/**
 * Kamado Breda – accessoires: de accessoires van de webshop (gietijzer, houtskool, ...), met bestelknop.
 * Hier staan de accessoires zelf; de winkelwagen gebruikt deze gegevens. Gebruik deze layout één keer.
 * Subtitle = klein label boven de titel. Title = titel. Content = intro.
 * Preview: card-grid
 * Setting: producten | list | Accessoires
 * Setting: producten[].id | text | ID | Alleen kleine letters, cijfers en -, bijv. "skillet". Niet meer wijzigen als er al bestellingen zijn.
 * Setting: producten[].naam | text | Naam
 * Setting: producten[].prijs | number | Prijs (incl. btw)
 * Setting: producten[].foto | image | Foto
 * Setting: producten[].tekst | textarea | Korte tekst
 * Setting: opmerking | textarea | Tekst onder de kaarten
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
?>
<section class="<?= cmsSectieKlasse($fpc_obj) ?>"<?= cms_anchor($fpc_obj) ?>>
    <div class="container">
        <?php include __DIR__ . '/../../includes/sectie-kop.php'; ?>
        <div class="grid grid-4">
            <?php foreach ($accessoires as $id => $item) {
                include __DIR__ . '/../../includes/accessoire-kaart.php';
            } ?>
        </div>
        <?php if (cmsVeld($fpc_obj, 'opmerking') !== ''): ?>
            <p class="note"><?= e(cmsVeld($fpc_obj, 'opmerking')) ?></p>
        <?php endif; ?>
    </div>
</section>
