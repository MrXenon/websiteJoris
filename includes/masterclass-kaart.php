<?php
defined('KAMADO_BREDA') || exit;

// Eén masterclass-kaart. Verwacht $id en $class (uit $masterclasses in data.php)
[$dag, $maand] = datumKort($class['datum']);
?>
<article class="card class-card reveal">
    <div class="card-image">
        <?= foto('thumbs/' . $class['foto'], $class['titel']) ?>
        <div class="date-badge"><strong><?= $dag ?></strong><?= $maand ?></div>
    </div>
    <div class="card-body">
        <p class="meta"><?= e($class['niveau']) ?> &middot; <?= e($class['tijd']) ?></p>
        <h3><?= e($class['titel']) ?></h3>
        <p><?= e($class['tekst']) ?></p>
        <p class="class-footer">
            <span class="price"><?= prijs($class['prijs']) ?> <small>p.p.</small></span>
            <span class="spots <?= $class['plekken'] <= 5 ? 'spots-low' : '' ?>">Nog <?= $class['plekken'] ?> plekken</span>
        </p>
        <a href="masterclasses?class=<?= e($id) ?>#inschrijven" class="btn btn-primary">Inschrijven</a>
    </div>
</article>
