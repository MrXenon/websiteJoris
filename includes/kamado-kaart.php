<?php
// Eén productkaart. Verwacht $id en $kamado (uit $kamados in data.php)
?>
<article class="card product-card">
    <a href="product.php?id=<?= e($id) ?>" class="card-image">
        <img src="images/thumbs/<?= e($kamado['foto']) ?>" alt="<?= e($kamado['naam']) ?>" loading="lazy">
        <span class="badge"><?= e($kamado['badge']) ?></span>
    </a>
    <div class="card-body">
        <h3><?= e($kamado['naam']) ?></h3>
        <p><?= e($kamado['kort']) ?></p>
        <p class="price"><?= prijs($kamado['prijs']) ?></p>
        <a href="product.php?id=<?= e($id) ?>" class="btn btn-outline">Bekijk details</a>
    </div>
</article>
