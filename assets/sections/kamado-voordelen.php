<?php
/**
 * Kamado Breda – voordelen: een balk met voordelen, elk met een icoon, titel en korte tekst.
 * Title, content en image worden niet gebruikt.
 * Preview: icon-row
 * Setting: voordelen | list | Voordelen
 * Setting: voordelen[].icoon | select(gereedschap=Gereedschap;vlam=Vlam;ster=Ster;vinkje=Vinkje;truck=Bezorging) | Icoon
 * Setting: voordelen[].titel | text | Titel
 * Setting: voordelen[].tekst | textarea | Tekst
 */
defined('KAMADO_BREDA') || exit;
$voordeelIconen = [
    'gereedschap' => '<path d="m15 12-8.5 8.5a2.12 2.12 0 1 1-3-3L12 9"/><path d="M17.64 15 22 10.64"/><path d="m20.91 11.7-1.25-1.25a2 2 0 0 1-.58-1.42V7.5L15.5 4l-1.4.03a3 3 0 0 0-2.11.9L11 6l4.5 4.5"/>',
    'vlam'        => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
    'ster'        => '<path d="M12 2 15.09 8.26 22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
    'vinkje'      => '<path d="M20 6 9 17l-5-5"/>',
    'truck'       => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62L18.3 9.38a1 1 0 0 0-.78-.38H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
];
?>
<section class="usps"<?= cms_anchor($fpc_obj) ?>>
    <div class="container usp-grid">
        <?php foreach (cmsLijst($fpc_obj, 'voordelen') as $voordeel): ?>
            <div class="usp reveal">
                <span class="usp-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $voordeelIconen[$voordeel['icoon'] ?? ''] ?? $voordeelIconen['vinkje'] ?></svg>
                </span>
                <h3><?= e($voordeel['titel'] ?? '') ?></h3>
                <p><?= e($voordeel['tekst'] ?? '') ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
