<?php
/**
 * Kamado Breda – masterclasses: de agenda met alle masterclasses.
 * Hier staan de masterclasses zelf; het inschrijfformulier en de homepage gebruiken deze gegevens.
 * Masterclasses waarvan de datum voorbij is, verdwijnen vanzelf van de site. Gebruik deze layout één keer.
 * Subtitle = klein label boven de titel. Title = titel. Content = intro.
 * Preview: card-grid
 * Setting: masterclasses | list | Masterclasses
 * Setting: masterclasses[].id | text | ID | Alleen kleine letters, cijfers en -, bijv. "low-slow"
 * Setting: masterclasses[].titel | text | Titel
 * Setting: masterclasses[].datum | text | Datum | Als JJJJ-MM-DD, bijv. 2026-11-14
 * Setting: masterclasses[].tijd | text | Tijd | Bijv. 09:00 – 17:00
 * Setting: masterclasses[].prijs | number | Prijs per persoon
 * Setting: masterclasses[].plekken | number | Vrije plekken
 * Setting: masterclasses[].niveau | text | Niveau | Bijv. Beginner
 * Setting: masterclasses[].foto | image | Foto
 * Setting: masterclasses[].tekst | textarea | Korte tekst
 * Setting: geenMasterclasses | text | Tekst als er geen masterclasses gepland zijn
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
?>
<section class="<?= cmsSectieKlasse($fpc_obj) ?>"<?= cms_anchor($fpc_obj) ?>>
    <div class="container">
        <?php include __DIR__ . '/../../includes/sectie-kop.php'; ?>
        <?php if ($masterclasses): ?>
            <div class="grid grid-3">
                <?php foreach ($masterclasses as $id => $class) {
                    include __DIR__ . '/../../includes/masterclass-kaart.php';
                } ?>
            </div>
        <?php elseif (cmsVeld($fpc_obj, 'geenMasterclasses') !== ''): ?>
            <p><?= e(cmsVeld($fpc_obj, 'geenMasterclasses')) ?></p>
        <?php endif; ?>
    </div>
</section>
