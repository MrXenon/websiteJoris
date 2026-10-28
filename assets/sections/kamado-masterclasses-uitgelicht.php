<?php
/**
 * Kamado Breda – komende masterclasses: de kaarten van de masterclasses, bijv. op de homepage.
 * De masterclasses zelf staan in het blok met de layout kamado-masterclasses (pagina masterclasses).
 * Zijn er geen masterclasses gepland, dan wordt dit blok niet getoond.
 * Subtitle = klein label boven de titel. Title = titel. Content = intro.
 * Preview: card-grid
 * Setting: link | group | Link rechts naast de titel
 * Setting: link.tekst | text | Tekst | Bijv. "Hele agenda"
 * Setting: link.link | url | Link | Bijv. "masterclasses"
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
if (!$masterclasses) {
    return;
}
?>
<section class="<?= cmsSectieKlasse($fpc_obj) ?>"<?= cms_anchor($fpc_obj) ?>>
    <div class="container">
        <?php include __DIR__ . '/../../includes/sectie-kop.php'; ?>
        <div class="grid grid-3">
            <?php foreach ($masterclasses as $id => $class) {
                include __DIR__ . '/../../includes/masterclass-kaart.php';
            } ?>
        </div>
    </div>
</section>
