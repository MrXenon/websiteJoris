<?php
defined('KAMADO_BREDA') || exit;

// Eén productkaart. Verwacht $id en $kamado (uit $kamados, zie data.php)
?>
<article class="card product-card reveal">
    <a href="kamados/<?= e($id) ?>" class="card-image" tabindex="-1" aria-hidden="true">
        <?= cmsFoto($kamado['foto'], $kamado['naam'], true) ?>
        <?php if ($kamado['badge'] !== ''): ?><span class="badge"><?= e($kamado['badge']) ?></span><?php endif; ?>
    </a>
    <div class="card-body">
        <h3><a href="kamados/<?= e($id) ?>" class="card-link"><?= e($kamado['naam']) ?></a></h3>
        <p><?= e($kamado['kort']) ?></p>
        <div class="card-footer">
            <p class="price"><?= prijs($kamado['prijs']) ?></p>
            <div class="card-actions">
                <?php $bestelId = $id; $metAantal = false; include __DIR__ . '/bestelknop.php'; ?>
                <a href="kamados/<?= e($id) ?>" class="btn btn-outline">Details</a>
            </div>
        </div>
    </div>
</article>
