<?php
/**
 * Kamado Breda – tekst: een smalle kolom met lopende tekst, bijv. de voorwaarden.
 * Title = titel boven de tekst (mag leeg). Content = de tekst, met tussenkopjes, opsommingen en links.
 * In de tekst worden {verzendkosten} en {gratis_bezorging_vanaf} vervangen door de bedragen uit de webshop-instellingen.
 * Blokken zonder layout ("default") worden ook zo getoond.
 * Preview: generic
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
$tekstHtml = strtr(cms_content($fpc_obj->getFpcontent()), [
    '{verzendkosten}'          => prijs($config['verzendkosten']),
    '{gratis_bezorging_vanaf}' => prijs($config['gratis_verzending_vanaf']),
]);
?>
<section class="<?= cmsSectieKlasse($fpc_obj) ?>"<?= cms_anchor($fpc_obj) ?>>
    <div class="container narrow prose">
        <?php if (cmsTekst($fpc_obj->getFptitle()) !== ''): ?>
            <h2><?= cmsHtml($fpc_obj->getFptitle()) ?></h2>
        <?php endif; ?>
        <?= $tekstHtml ?>
    </div>
</section>
