<?php
session_start();

// Alleen tonen als iemand net het formulier heeft verstuurd
if (!isset($_SESSION['aanmelding'])) {
    header('Location: masterclass.php');
    exit;
}

$aanmelding = $_SESSION['aanmelding'];
// Na het tonen weghalen, zodat de gegevens niet in de sessie blijven staan
unset($_SESSION['aanmelding']);

$ervaringTekst = [
    'geen'   => 'Nog geen ervaring',
    'beetje' => 'Een beetje ervaring',
    'veel'   => 'Veel ervaring',
];

$paginaTitel = 'Inschrijving bevestigd';
$actief = 'masterclass.php';
require 'includes/header.php';
?>

<section class="section">
    <div class="container narrow">
        <div class="confirmation">
            <div class="confirmation-icon" aria-hidden="true">&#10003;</div>
            <h1>Bedankt, <?= e($aanmelding['voornaam']) ?>!</h1>
            <p class="lead">
                Je bent ingeschreven voor <strong><?= e($aanmelding['masterclass']) ?></strong>.
                We sturen de details naar <strong><?= e($aanmelding['email']) ?></strong>.
            </p>

            <table class="specs">
                <tr><th scope="row">Masterclass</th><td><?= e($aanmelding['masterclass']) ?></td></tr>
                <tr><th scope="row">Datum</th><td><?= e(datumNL($aanmelding['datum'])) ?></td></tr>
                <tr><th scope="row">Tijd</th><td><?= e($aanmelding['tijd']) ?></td></tr>
                <tr><th scope="row">Naam</th><td><?= e($aanmelding['voornaam'] . ' ' . $aanmelding['achternaam']) ?></td></tr>
                <?php if ($aanmelding['telefoon'] !== ''): ?>
                    <tr><th scope="row">Telefoon</th><td><?= e($aanmelding['telefoon']) ?></td></tr>
                <?php endif; ?>
                <tr><th scope="row">Aantal personen</th><td><?= e($aanmelding['personen']) ?></td></tr>
                <tr><th scope="row">Ervaring</th><td><?= e($ervaringTekst[$aanmelding['ervaring']]) ?></td></tr>
                <?php if ($aanmelding['wensen'] !== ''): ?>
                    <tr><th scope="row">Opmerkingen</th><td><?= nl2br(e($aanmelding['wensen'])) ?></td></tr>
                <?php endif; ?>
                <tr class="total-row"><th scope="row">Totaal</th><td><?= prijs($aanmelding['totaal']) ?></td></tr>
            </table>

            <p>Je betaalt op de dag zelf in de winkel (pin of contant). Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

            <div class="hero-buttons center">
                <a href="index.php" class="btn btn-primary">Terug naar home</a>
                <a href="kamados.php" class="btn btn-outline">Bekijk de kamado's</a>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
