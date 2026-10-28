<?php
/**
 * Kamado Breda – oproep: oranje balk met een titel, korte tekst en een knop (bijv. "Schrijf je in").
 * Title = titel. Content = korte tekst.
 * Preview: band
 * Setting: knop | group | Knop
 * Setting: knop.tekst | text | Tekst
 * Setting: knop.link | url | Link | Bijv. "masterclasses#inschrijven"
 */
defined('KAMADO_BREDA') || exit;
$oproepKnop = cmsVeld($fpc_obj, 'knop', []);
?>
<section class="cta"<?= cms_anchor($fpc_obj) ?>>
    <div class="container cta-inner">
        <h2><?= cmsHtml($fpc_obj->getFptitle()) ?></h2>
        <?= cms_content($fpc_obj->getFpcontent()) ?>
        <?php if (is_array($oproepKnop) && trim((string) ($oproepKnop['tekst'] ?? '')) !== ''): ?>
            <a href="<?= e(cmsLink($oproepKnop['link'] ?? '')) ?>" class="btn btn-dark btn-large"><?= e($oproepKnop['tekst']) ?></a>
        <?php endif; ?>
    </div>
</section>
