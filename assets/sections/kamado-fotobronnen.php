<?php
/**
 * Kamado Breda – fotobronnen: tabel met de makers en licenties van de foto's op de site.
 * Title = titel van de pagina (h1). Content = uitleg boven de tabel.
 * Preview: list-image
 * Setting: bronnen | list | Foto's
 * Setting: bronnen[].bestand | text | Bestand(en) | Bijv. "actie-ribs.jpg"
 * Setting: bronnen[].maker | text | Maker
 * Setting: bronnen[].licentie | text | Licentie | Bijv. "CC BY-SA 2.0"
 * Setting: bronnen[].url | url | Link naar de bron | Mag leeg
 */
defined('KAMADO_BREDA') || exit;
?>
<section class="section"<?= cms_anchor($fpc_obj) ?>>
    <div class="container narrow">
        <h1><?= cmsHtml($fpc_obj->getFptitle()) ?></h1>
        <?= cms_content($fpc_obj->getFpcontent()) ?>

        <table class="specs credits">
            <thead>
                <tr><th>Bestand</th><th>Maker</th><th>Licentie</th></tr>
            </thead>
            <tbody>
                <?php foreach ($fotobronnen as $bron): ?>
                    <tr>
                        <td><?= e($bron['bestand']) ?></td>
                        <td>
                            <?php if ($bron['url'] !== ''): ?>
                                <a href="<?= e(cmsLink($bron['url'])) ?>" target="_blank" rel="noopener"><?= e($bron['maker']) ?></a>
                            <?php else: ?>
                                <?= e($bron['maker']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= e($bron['licentie']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
