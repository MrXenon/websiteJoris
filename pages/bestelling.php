<?php
defined('KAMADO_BREDA') || exit;

// Bestelnummer komt uit de URL: /bestelling/KB-20261028-AB12CD
$nummer = $parameter ?? '';
$bestelling = laadBestelling($nummer);

// Alleen tonen aan degene die de bestelling heeft geplaatst (staat in zijn/haar sessie)
if ($bestelling === null || !in_array($nummer, $_SESSION['mijn_bestellingen'] ?? [], true)) {
    require __DIR__ . '/404.php';
    return;
}

// Terug van Mollie? Dan de actuele status van de betaling ophalen.
if ($bestelling['status'] === 'open') {
    werkMollieStatusBij($bestelling);
}

$gelukt = in_array($bestelling['status'], ['betaald', 'betalen-bij-afhalen']);
if ($gelukt) {
    leegWinkelwagen();
    unset($_SESSION['afrekenen']);
}

$k = $bestelling['klant'];
$paginaTitel = $gelukt ? 'Bedankt voor je bestelling' : 'Status van je bestelling';
$actief = 'winkelwagen';
$geenIndex = true;
require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container narrow">
        <?php $stap = $gelukt ? 4 : 3; include __DIR__ . '/../includes/checkout-stappen.php'; ?>

        <div class="confirmation">
            <?php if ($gelukt): ?>
                <div class="confirmation-icon" aria-hidden="true">&#10003;</div>
                <h1>Bedankt, <?= e($k['voornaam']) ?>!</h1>
                <p class="lead">
                    <?php if ($bestelling['status'] === 'betaald'): ?>
                        Je betaling is gelukt en je bestelling <strong><?= e($bestelling['nummer']) ?></strong> is bevestigd.
                    <?php else: ?>
                        Je bestelling <strong><?= e($bestelling['nummer']) ?></strong> is geplaatst. Je betaalt bij het ophalen in de winkel.
                    <?php endif; ?>
                    We sturen de details naar <strong><?= e($k['email']) ?></strong>.
                </p>
            <?php elseif ($bestelling['status'] === 'open'): ?>
                <div class="confirmation-icon confirmation-wait" aria-hidden="true">&hellip;</div>
                <h1>We wachten op je betaling</h1>
                <p class="lead">Zodra de betaling binnen is, wordt je bestelling <strong><?= e($bestelling['nummer']) ?></strong> bevestigd. Dit duurt meestal een paar seconden.</p>
                <div class="hero-buttons center">
                    <a href="bestelling/<?= e($bestelling['nummer']) ?>" class="btn btn-primary">Status vernieuwen</a>
                </div>
            <?php else: ?>
                <div class="confirmation-icon confirmation-error" aria-hidden="true">&times;</div>
                <h1>De betaling is niet gelukt</h1>
                <p class="lead">Je betaling is <?= e($bestelling['status']) ?>. Er is niets afgeschreven. Je winkelwagen staat nog voor je klaar.</p>
                <div class="hero-buttons center">
                    <a href="afrekenen" class="btn btn-primary">Opnieuw proberen</a>
                    <a href="winkelwagen" class="btn btn-outline">Naar winkelwagen</a>
                </div>
            <?php endif; ?>

            <table class="specs">
                <thead>
                    <tr><th>Artikel</th><th class="num">Totaal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($bestelling['regels'] as $regel): ?>
                        <tr>
                            <td><?= $regel['aantal'] ?>&times; <?= e($regel['naam']) ?></td>
                            <td class="num"><?= prijs($regel['totaal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><?= $bestelling['levering'] === 'bezorgen' ? 'Bezorgen' : 'Afhalen in de winkel' ?></td>
                        <td class="num"><?= $bestelling['verzendkosten'] > 0 ? prijs($bestelling['verzendkosten']) : 'Gratis' ?></td>
                    </tr>
                    <tr class="total-row">
                        <td>Totaal <small>(incl. <?= prijs($bestelling['btw']) ?> btw)</small></td>
                        <td class="num"><?= prijs($bestelling['totaal']) ?></td>
                    </tr>
                </tbody>
            </table>

            <?php if ($gelukt): ?>
                <div class="next-steps">
                    <h2>Hoe nu verder?</h2>
                    <?php if ($bestelling['levering'] === 'bezorgen'): ?>
                        <p>We bellen je binnen 2 werkdagen op <strong><?= e($k['telefoon']) ?></strong> om een bezorgmoment af te spreken op
                            <strong><?= e($k['straat'] . ' ' . $k['huisnummer'] . ', ' . $k['plaats']) ?></strong>.</p>
                    <?php else: ?>
                        <p>Je bestelling ligt vanaf de volgende werkdag klaar in de winkel aan de Voorbeeldstraat 12 in Breda.
                            Neem je bestelnummer mee.</p>
                    <?php endif; ?>
                </div>
                <div class="hero-buttons center">
                    <a href="masterclasses" class="btn btn-primary">Bekijk de masterclasses</a>
                    <a href="./" class="btn btn-outline">Terug naar home</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
