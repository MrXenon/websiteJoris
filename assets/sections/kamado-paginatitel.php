<?php
/**
 * Kamado Breda – paginatitel: donkere balk bovenaan een pagina met de titel (h1).
 * Subtitle = klein oranje label boven de titel. Title = titel van de pagina. Content = korte intro (mag leeg).
 * Image = achtergrondfoto (mag leeg).
 * Preview: hero
 */
defined('KAMADO_BREDA') || exit;
$titelAchtergrond = cmsAchtergrond($fpc_obj->getFpimage());
?>
<section class="page-hero<?= $titelAchtergrond !== '' ? ' page-hero-image' : '' ?>"<?= cms_anchor($fpc_obj) ?><?= $titelAchtergrond !== '' ? ' style="' . e($titelAchtergrond) . '"' : '' ?>>
    <div class="container">
        <?php if (cmsTekst($fpc_obj->getFpsubtitle()) !== ''): ?>
            <p class="eyebrow"><?= e(cmsTekst($fpc_obj->getFpsubtitle())) ?></p>
        <?php endif; ?>
        <h1><?= cmsHtml($fpc_obj->getFptitle()) ?></h1>
        <?= cms_content($fpc_obj->getFpcontent()) ?>
    </div>
</section>
