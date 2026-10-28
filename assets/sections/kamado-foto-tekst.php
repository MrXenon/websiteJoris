<?php
/**
 * Kamado Breda – foto en tekst naast elkaar, bijv. "Over ons" of "Wat kun je verwachten?".
 * Subtitle = klein label boven de titel. Title = titel. Image = foto.
 * Content = tekst. Een opsomming krijgt oranje vinkjes; een citaat (blockquote) mag ook, met de naam in <cite>.
 * Preview: image-text
 * Setting: fotoAlt | text | Omschrijving van de foto | Voor blinden en zoekmachines
 * Setting: fotoPositie | select(links=Foto links;rechts=Foto rechts) | Foto
 * Setting: knop | group | Knop onder de tekst
 * Setting: knop.tekst | text | Tekst
 * Setting: knop.link | url | Link
 * Setting: achtergrond | select(=Wit;licht=Licht;donker=Donker) | Achtergrond
 */
defined('KAMADO_BREDA') || exit;
$splitKnop = cmsVeld($fpc_obj, 'knop', []);
$splitFoto = cmsFoto($fpc_obj->getFpimage(), cmsVeld($fpc_obj, 'fotoAlt'));
$splitFotoHtml = $splitFoto !== '' ? '<div class="split-image reveal">' . $splitFoto . '</div>' : '';
?>
<section class="<?= cmsSectieKlasse($fpc_obj) ?>"<?= cms_anchor($fpc_obj) ?>>
    <div class="container split">
        <?= cmsVeld($fpc_obj, 'fotoPositie', 'links') === 'links' ? $splitFotoHtml : '' ?>
        <div class="split-text reveal">
            <?php if (cmsTekst($fpc_obj->getFpsubtitle()) !== ''): ?>
                <p class="eyebrow"><?= e(cmsTekst($fpc_obj->getFpsubtitle())) ?></p>
            <?php endif; ?>
            <h2><?= cmsHtml($fpc_obj->getFptitle()) ?></h2>
            <?= cms_add_class(cms_content($fpc_obj->getFpcontent()), 'ul', 'check-list') ?>
            <?php if (is_array($splitKnop) && trim((string) ($splitKnop['tekst'] ?? '')) !== ''): ?>
                <a href="<?= e(cmsLink($splitKnop['link'] ?? '')) ?>" class="btn btn-outline"><?= e($splitKnop['tekst']) ?></a>
            <?php endif; ?>
        </div>
        <?= cmsVeld($fpc_obj, 'fotoPositie', 'links') !== 'links' ? $splitFotoHtml : '' ?>
    </div>
</section>
