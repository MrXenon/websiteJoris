<?php
defined('KAMADO_BREDA') || exit;

// Herbruikbare fotogalerij.
// Verwacht een variabele $galerij = [ ['bestand.jpg', 'alt tekst'], ... ] met foto's uit het CMS
// (images/frontendPagesContent/, zie cmsFoto() in cms.php).
// De interactie (klikken, pijltjes, vergroten) zit in js/gallery.js
$eerste = $galerij[0];
// Zonder WebP-versie (bijv. een nieuwe upload in het CMS) gewoon de foto zelf
$webp = function ($bestand) {
    return cmsFotoUrl($bestand, true) ?: cmsFotoUrl($bestand);
};
?>
<div class="gallery" data-gallery>
    <div class="gallery-main">
        <picture>
            <source srcset="<?= e($webp($eerste[0])) ?>" type="<?= cmsFotoUrl($eerste[0], true) ? 'image/webp' : 'image/jpeg' ?>" data-gallery-source>
            <img src="<?= e(cmsFotoUrl($eerste[0])) ?>" alt="<?= e($eerste[1]) ?>" class="gallery-image" data-gallery-image fetchpriority="high">
        </picture>

        <button class="gallery-btn gallery-prev" data-gallery-prev aria-label="Vorige foto">&#10094;</button>
        <button class="gallery-btn gallery-next" data-gallery-next aria-label="Volgende foto">&#10095;</button>
        <button class="gallery-zoom" data-gallery-zoom aria-label="Foto vergroten">&#x26F6;</button>

        <p class="gallery-counter" data-gallery-counter>1 / <?= count($galerij) ?></p>
    </div>

    <ul class="gallery-thumbs">
        <?php foreach ($galerij as $i => $foto): ?>
            <li>
                <button class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>"
                        data-full="<?= e(cmsFotoUrl($foto[0])) ?>"
                        data-full-webp="<?= e($webp($foto[0])) ?>"
                        data-alt="<?= e($foto[1]) ?>"
                        aria-label="Toon foto <?= $i + 1 ?>: <?= e($foto[1]) ?>">
                    <?= cmsFoto($foto[0], '', true) ?>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
