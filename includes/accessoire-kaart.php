<?php
defined('KAMADO_BREDA') || exit;

// Eén accessoire-kaart. Verwacht $id en $item (uit $accessoires in data.php)
?>
<article class="card reveal">
    <div class="card-image">
        <?= foto('thumbs/' . $item['foto'], $item['naam']) ?>
    </div>
    <div class="card-body">
        <h3><?= e($item['naam']) ?></h3>
        <p><?= e($item['tekst']) ?></p>
        <div class="card-footer">
            <p class="price"><?= prijs($item['prijs']) ?></p>
            <?php $bestelId = $id; $metAantal = false; include __DIR__ . '/bestelknop.php'; ?>
        </div>
    </div>
</article>
