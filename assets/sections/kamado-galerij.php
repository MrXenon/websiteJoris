<?php
/**
 * Kamado Breda – fotogalerij: grote foto met kleine foto's eronder, bladeren met pijltjes en vergroten.
 * Title, content en image worden niet gebruikt.
 * Preview: slider
 * Setting: fotos | list | Foto's
 * Setting: fotos[].foto | image | Foto
 * Setting: fotos[].alt | text | Omschrijving | Voor blinden en zoekmachines
 */
defined('KAMADO_BREDA') || exit;
$galerij = [];
foreach (cmsLijst($fpc_obj, 'fotos') as $galerijFoto) {
    if (trim((string) ($galerijFoto['foto'] ?? '')) !== '') {
        $galerij[] = [$galerijFoto['foto'], (string) ($galerijFoto['alt'] ?? '')];
    }
}
if (!$galerij) {
    return;
}
// js/gallery.js laadt de header (includes/header.php) voor pagina's met deze layout
?>
<section class="section section-tight"<?= cms_anchor($fpc_obj) ?>>
    <div class="container">
        <?php include __DIR__ . '/../../includes/gallery.php'; ?>
    </div>
</section>
