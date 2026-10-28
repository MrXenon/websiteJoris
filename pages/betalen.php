<?php
defined('KAMADO_BREDA') || exit;

// Betaalstap zolang er nog geen Mollie API-sleutel is ingesteld (demo van de webshop).
// Er wordt niet echt betaald; in data/bestellingen.csv krijgt zo'n bestelling de status "betaald (test)".
// Met een Mollie-sleutel bestaat deze pagina niet: dan betaal je bij Mollie.
$nummer = $parameter ?? '';
$bestelling = laadBestelling($nummer);

if (mollieActief() || $bestelling === null || $bestelling['status'] !== 'open'
    || !in_array($nummer, $_SESSION['mijn_bestellingen'] ?? [], true)) {
    require __DIR__ . '/404.php';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfOk()) {
    $uitkomst = ($_POST['uitkomst'] ?? '') === 'betaald' ? 'betaald' : 'geannuleerd';
    $bestelling['test'] = true;
    zetStatus($bestelling, $uitkomst);
    stuurDoor('bestelling/' . $nummer);
}

$paginaTitel = 'Betalen';
$actief = 'winkelwagen';
$geenIndex = true;
require __DIR__ . '/../includes/header.php';
?>

<section class="section section-tight">
    <div class="container narrow">
        <?php $stap = 3; include __DIR__ . '/../includes/checkout-stappen.php'; ?>
        <h1>Betalen</h1>

        <form method="post" class="form payment-box">
            <?= csrfVeld() ?>

            <p class="payment-amount">
                <span>Bestelling <?= e($bestelling['nummer']) ?></span>
                <strong><?= prijs($bestelling['totaal']) ?></strong>
            </p>

            <fieldset class="form-section">
                <legend>Kies een betaalmethode</legend>
                <div class="choice-group choice-group-stack">
                    <?php foreach (['iDEAL' => 'Betaal direct via je eigen bank', 'Bancontact' => 'Voor Belgische bankpassen', 'Creditcard' => 'Visa, Mastercard of American Express'] as $methode => $uitleg): ?>
                        <label class="choice">
                            <input type="radio" name="methode" value="<?= e($methode) ?>" <?= $methode === 'iDEAL' ? 'checked' : '' ?>>
                            <span><strong><?= e($methode) ?></strong><small><?= e($uitleg) ?></small></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div class="hero-buttons">
                <button type="submit" name="uitkomst" value="betaald" class="btn btn-primary btn-large">Betalen</button>
                <button type="submit" name="uitkomst" value="geannuleerd" class="btn btn-outline btn-large">Annuleren</button>
            </div>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
