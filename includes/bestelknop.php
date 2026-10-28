<?php
defined('KAMADO_BREDA') || exit;

// Bestelknop: zet een product in de winkelwagen.
// Verwacht $bestelId (bijv. 'blue-l' of 'skillet'). Optioneel $metAantal = true voor een aantal-veld.
// Werkt zonder JavaScript (gewoon formulier); met JavaScript zonder de pagina te herladen (js/app.js).
?>
<form action="winkelwagen" method="post" class="add-to-cart" data-add-to-cart>
    <?= csrfVeld() ?>
    <input type="hidden" name="actie" value="toevoegen">
    <input type="hidden" name="product" value="<?= e($bestelId) ?>">
    <?php if (!empty($metAantal)): ?>
        <label class="qty">
            <span class="visually-hidden">Aantal</span>
            <input type="number" name="aantal" value="1" min="1" max="<?= (int) $config['max_aantal'] ?>" inputmode="numeric">
        </label>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary <?= !empty($metAantal) ? 'btn-large' : '' ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Bestellen
    </button>
</form>
