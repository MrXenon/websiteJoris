<?php
/**
 * Kamado Breda – uitgelichte kamado's: de kaarten van de kamado's, bijv. op de homepage.
 * De kamado's zelf staan in het blok met de layout kamado-kamados (pagina kamados).
 * Subtitle = klein label boven de titel. Title = titel. Content = intro.
 * Preview: card-grid
 * Setting: link | group | Link rechts naast de titel
 * Setting: link.tekst | text | Tekst | Bijv. "Alles bekijken"
 * Setting: link.link | url | Link | Bijv. "kamados"
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
if (!$kamados) {
    return;
}
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
