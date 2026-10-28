<?php
/**
 * Kamado Breda – hero: grote foto met titel, tekst en knoppen (bovenaan de homepage).
 * Subtitle = klein oranje label boven de titel. Title = grote titel; <br> en <span class="text-fire">tekst</span> (oranje) mogen.
 * Content = tekst onder de titel. Image = achtergrondfoto (leeg = de foto van de winkel).
 * Preview: hero
 * Setting: knoppen | list | Knoppen
 * Setting: knoppen[].tekst | text | Tekst
 * Setting: knoppen[].link | url | Link | Bijv. "kamados" of "masterclasses#inschrijven"
 * Setting: knoppen[].stijl | select(primary=Oranje;ghost=Doorzichtig) | Stijl
 * Setting: voordelen | textarea | Voordelen onder de knoppen | Eén per regel
 */
defined('KAMADO_BREDA') || exit;
$heroAchtergrond = cmsAchtergrond($fpc_obj->getFpimage());
?>
<section class="hero"<?= cms_anchor($fpc_obj) ?><?= $heroAchtergrond !== '' ? ' style="' . e($heroAchtergrond) . '"' : '' ?>>
    <div class="container hero-content">
        <?php if (cmsTekst($fpc_obj->getFpsubtitle()) !== ''): ?>
            <p class="eyebrow"><?= e(cmsTekst($fpc_obj->getFpsubtitle())) ?></p>
        <?php endif; ?>
        <h1><?= cmsHtml($fpc_obj->getFptitle()) ?></h1>
        <?php if (cmsTekst($fpc_obj->getFpcontent()) !== ''): ?>
            <?= cms_add_class(cms_content($fpc_obj->getFpcontent()), 'p', 'hero-text') ?>
        <?php endif; ?>
        <?php if (cmsLijst($fpc_obj, 'knoppen')): ?>
            <div class="hero-buttons">
                <?php foreach (cmsLijst($fpc_obj, 'knoppen') as $knop): ?>
                    <a href="<?= e(cmsLink($knop['link'] ?? '')) ?>" class="btn btn-<?= ($knop['stijl'] ?? '') === 'ghost' ? 'ghost' : 'primary' ?> btn-large"><?= e($knop['tekst'] ?? '') ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (cmsRegels(cmsVeld($fpc_obj, 'voordelen'))): ?>
            <ul class="hero-trust">
                <?php foreach (cmsRegels(cmsVeld($fpc_obj, 'voordelen')) as $voordeel): ?>
                    <li><?= e($voordeel) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
