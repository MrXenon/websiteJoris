<?php
defined('KAMADO_BREDA') || exit;

// Stappenbalk boven winkelwagen, afrekenen en bevestiging. Verwacht $stap (1, 2 of 3).
$stappen = [1 => 'Winkelwagen', 2 => 'Gegevens', 3 => 'Betalen'];
?>
<ol class="steps" aria-label="Stappen van je bestelling">
    <?php foreach ($stappen as $nummer => $naam): ?>
        <li class="<?= $nummer < $stap ? 'done' : '' ?> <?= $nummer === $stap ? 'current' : '' ?>"
            <?= $nummer === $stap ? 'aria-current="step"' : '' ?>>
            <span class="step-nr"><?= $nummer ?></span> <?= $naam ?>
        </li>
    <?php endforeach; ?>
</ol>
