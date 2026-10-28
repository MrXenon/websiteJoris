<?php
$paginaTitel = 'Fotobronnen';
require 'includes/header.php';
?>

<section class="section">
    <div class="container narrow">
        <h1>Fotobronnen</h1>
        <p>Een deel van de foto's op deze website komt van Wikimedia Commons. Ze zijn bijgesneden en verkleind.
           Hieronder staat per foto wie hem gemaakt heeft en onder welke licentie.</p>

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
                                <a href="<?= e($bron['url']) ?>" target="_blank" rel="noopener"><?= e($bron['maker']) ?></a>
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

<?php require 'includes/footer.php'; ?>
