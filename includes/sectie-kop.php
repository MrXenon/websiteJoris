<?php
defined('KAMADO_BREDA') || exit;

// Kop van een sectie uit het CMS: label (subtitle), titel en intro (content),
// met rechts een link als het blok de instelling "link" heeft (tekst + link).
// Verwacht $fpc_obj (het blok).
$kopLink = cmsVeld($fpc_obj, 'link', []);
$kopLink = is_array($kopLink) && trim((string) ($kopLink['tekst'] ?? '')) !== '' ? $kopLink : null;
$kopLabel = cmsTekst($fpc_obj->getFpsubtitle());
$kopTitel = cmsHtml($fpc_obj->getFptitle());
$kopIntro = cms_content($fpc_obj->getFpcontent());

if ($kopLabel === '' && $kopTitel === '' && cmsTekst($kopIntro) === '' && !$kopLink) {
    return;
}
?>
<div class="section-head<?= $kopLink ? ' section-head-row' : '' ?>">
    <?php if ($kopLink): ?><div><?php endif; ?>
        <?php if ($kopLabel !== ''): ?>
            <p class="eyebrow"><?= e($kopLabel) ?></p>
        <?php endif; ?>
        <?php if ($kopTitel !== ''): ?>
            <h2><?= $kopTitel ?></h2>
        <?php endif; ?>
        <?= $kopIntro ?>
    <?php if ($kopLink): ?></div>
        <a href="<?= e(cmsLink($kopLink['link'] ?? '')) ?>" class="link-arrow"><?= e($kopLink['tekst']) ?></a>
    <?php endif; ?>
</div>
