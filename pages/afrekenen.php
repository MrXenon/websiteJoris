<?php
defined('KAMADO_BREDA') || exit;

// Lege winkelwagen? Dan valt er niks af te rekenen.
if (aantalInWinkelwagen() === 0) {
    stuurDoor('winkelwagen');
}

$fouten = [];

// Standaardwaarden van het formulier (of de vorige keer ingevuld, uit de sessie)
$invoer = ($_SESSION['afrekenen'] ?? []) + [
    'voornaam'    => '',
    'achternaam'  => '',
    'email'       => '',
    'telefoon'    => '',
    'levering'    => 'bezorgen',
    'postcode'    => '',
    'huisnummer'  => '',
    'straat'      => '',
    'plaats'      => '',
    'betaalwijze' => 'online',
    'opmerkingen' => '',
    'akkoord'     => '',
];

// ---------- Formulier verwerken ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Alle velden ophalen uit $_POST en spaties weghalen
    foreach ($invoer as $veld => $waarde) {
        $invoer[$veld] = trim((string) ($_POST[$veld] ?? ''));
    }
    $invoer['postcode'] = strtoupper(str_replace(' ', '', $invoer['postcode']));
    $bezorgen = $invoer['levering'] === 'bezorgen';

    // Honeypot en CSRF: alleen spambots en andere websites gaan hier fout
    if (!empty($_POST['website']) || !csrfOk()) {
        $fouten['algemeen'] = 'Er ging iets mis, probeer het opnieuw.';
    }

    // Controleren
    if ($invoer['voornaam'] === '' || mb_strlen($invoer['voornaam']) > 50) {
        $fouten['voornaam'] = 'Vul je voornaam in.';
    }
    if ($invoer['achternaam'] === '' || mb_strlen($invoer['achternaam']) > 80) {
        $fouten['achternaam'] = 'Vul je achternaam in.';
    }
    if (!filter_var($invoer['email'], FILTER_VALIDATE_EMAIL)) {
        $fouten['email'] = 'Vul een geldig e-mailadres in.';
    }
    if (!preg_match('/^[0-9 +()-]{10,15}$/', $invoer['telefoon'])) {
        $fouten['telefoon'] = 'Vul een geldig telefoonnummer in, zodat we je kunnen bellen over de levering.';
    }
    if (!in_array($invoer['levering'], ['bezorgen', 'afhalen'])) {
        $fouten['levering'] = 'Kies bezorgen of afhalen.';
    }
    if ($bezorgen) {
        if (!preg_match('/^[1-9][0-9]{3}[A-Z]{2}$/', $invoer['postcode'])) {
            $fouten['postcode'] = 'Vul een geldige postcode in, bijv. 4811 AB.';
        } else {
            $invoer['postcode'] = substr($invoer['postcode'], 0, 4) . ' ' . substr($invoer['postcode'], 4);
        }
        if (!preg_match('/^[0-9]{1,5}\s?[a-zA-Z0-9-]{0,6}$/', $invoer['huisnummer'])) {
            $fouten['huisnummer'] = 'Vul je huisnummer in.';
        }
        if ($invoer['straat'] === '' || mb_strlen($invoer['straat']) > 100) {
            $fouten['straat'] = 'Vul je straat in.';
        }
        if ($invoer['plaats'] === '' || mb_strlen($invoer['plaats']) > 60) {
            $fouten['plaats'] = 'Vul je woonplaats in.';
        }
    }
    // Betalen bij afhalen kan alleen als je ook afhaalt
    if (!in_array($invoer['betaalwijze'], ['online', 'afhalen']) || ($bezorgen && $invoer['betaalwijze'] === 'afhalen')) {
        $invoer['betaalwijze'] = 'online';
    }
    if (mb_strlen($invoer['opmerkingen']) > 500) {
        $fouten['opmerkingen'] = 'Maximaal 500 tekens.';
    }
    if ($invoer['akkoord'] !== 'ja') {
        $fouten['akkoord'] = 'Je moet akkoord gaan met de voorwaarden om te bestellen.';
    }

    // Ingevulde gegevens onthouden (handig als de betaling mislukt en je het opnieuw probeert)
    $_SESSION['afrekenen'] = array_diff_key($invoer, ['akkoord' => '']);

    // Geen fouten? Bestelling opslaan en door naar betalen
    if (empty($fouten)) {
        $wagen = berekenWinkelwagen($bezorgen);

        $klant = $invoer;
        unset($klant['levering'], $klant['betaalwijze'], $klant['akkoord']);
        if (!$bezorgen) {
            $klant['postcode'] = $klant['huisnummer'] = $klant['straat'] = $klant['plaats'] = '';
        }

        $bestelling = [
            'nummer'        => nieuwBestelnummer(),
            'aangemaakt'    => date('Y-m-d H:i:s'),
            'status'        => 'open',
            'levering'      => $invoer['levering'],
            'betaalwijze'   => $invoer['betaalwijze'],
            'klant'         => $klant,
            'regels'        => array_values(array_map(function ($regel) {
                return [
                    'id'     => $regel['id'],
                    'naam'   => $regel['naam'],
                    'prijs'  => $regel['prijs'],
                    'aantal' => $regel['aantal'],
                    'totaal' => $regel['totaal'],
                ];
            }, $wagen['regels'])),
            'subtotaal'     => $wagen['subtotaal'],
            'verzendkosten' => $wagen['verzendkosten'],
            'btw'           => $wagen['btw'],
            'totaal'        => $wagen['totaal'],
        ];
        bewaarBestelling($bestelling);

        // Alleen wie de bestelling heeft geplaatst mag de bestelpagina zien
        $_SESSION['mijn_bestellingen'][] = $bestelling['nummer'];

        if ($bestelling['betaalwijze'] === 'afhalen') {
            zetStatus($bestelling, 'betalen-bij-afhalen');
            stuurDoor('bestelling/' . $bestelling['nummer']);
        }

        if (!mollieActief()) {
            // Nog geen Mollie-sleutel: eigen betaalstap (demo, er wordt niet echt betaald)
            stuurDoor('betalen/' . $bestelling['nummer']);
        }

        $betaalUrl = maakMollieBetaling($bestelling);
        if ($betaalUrl) {
            header('Location: ' . $betaalUrl, true, 303);
            exit;
        }
        $fouten['algemeen'] = 'Het lukte niet om de betaling te starten. Probeer het later opnieuw of neem contact met ons op.';
    }
}

// Hulpje: foutmelding onder een veld tonen
function fout($veld, $fouten)
{
    if (isset($fouten[$veld])) {
        return '<p class="field-error" id="fout-' . $veld . '">' . e($fouten[$veld]) . '</p>';
    }
    return '';
}

// Hulpje: class en aria-attributen voor een veld met (of zonder) fout
function veldFout($veld, $fouten)
{
    return isset($fouten[$veld]) ? 'aria-invalid="true" aria-describedby="fout-' . $veld . '"' : '';
}

$bezorgen = $invoer['levering'] === 'bezorgen';
$wagen = berekenWinkelwagen($bezorgen);
$wagenBezorgen = berekenWinkelwagen(true);

$paginaTitel = 'Afrekenen';
$actief = 'winkelwagen';
$geenIndex = true;
$scripts = ['checkout.js'];
require __DIR__ . '/../includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <?php $stap = 2; include __DIR__ . '/../includes/checkout-stappen.php'; ?>
        <h1>Afrekenen</h1>

        <div class="checkout-layout">
            <form action="afrekenen" method="post" class="form" id="afrekenformulier" novalidate>
                <?= csrfVeld() ?>

                <?php if (!empty($fouten)): ?>
                    <div class="alert alert-error" role="alert">
                        <strong>Niet alles is goed ingevuld.</strong> Controleer de rode velden hieronder.
                        <?= isset($fouten['algemeen']) ? '<br>' . e($fouten['algemeen']) : '' ?>
                    </div>
                <?php endif; ?>

                <fieldset class="form-section">
                    <legend>Jouw gegevens</legend>
                    <div class="field-row">
                        <div class="field <?= isset($fouten['voornaam']) ? 'has-error' : '' ?>">
                            <label for="voornaam">Voornaam *</label>
                            <input type="text" name="voornaam" id="voornaam" value="<?= e($invoer['voornaam']) ?>" maxlength="50" autocomplete="given-name" required <?= veldFout('voornaam', $fouten) ?>>
                            <?= fout('voornaam', $fouten) ?>
                        </div>
                        <div class="field <?= isset($fouten['achternaam']) ? 'has-error' : '' ?>">
                            <label for="achternaam">Achternaam *</label>
                            <input type="text" name="achternaam" id="achternaam" value="<?= e($invoer['achternaam']) ?>" maxlength="80" autocomplete="family-name" required <?= veldFout('achternaam', $fouten) ?>>
                            <?= fout('achternaam', $fouten) ?>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field <?= isset($fouten['email']) ? 'has-error' : '' ?>">
                            <label for="email">E-mailadres *</label>
                            <input type="email" name="email" id="email" value="<?= e($invoer['email']) ?>" autocomplete="email" required <?= veldFout('email', $fouten) ?>>
                            <?= fout('email', $fouten) ?>
                        </div>
                        <div class="field <?= isset($fouten['telefoon']) ? 'has-error' : '' ?>">
                            <label for="telefoon">Telefoon *</label>
                            <input type="tel" name="telefoon" id="telefoon" value="<?= e($invoer['telefoon']) ?>" autocomplete="tel" required <?= veldFout('telefoon', $fouten) ?>>
                            <?= fout('telefoon', $fouten) ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form-section">
                    <legend>Bezorgen of afhalen</legend>
                    <div class="choice-group">
                        <label class="choice">
                            <input type="radio" name="levering" value="bezorgen" <?= $bezorgen ? 'checked' : '' ?> data-levering>
                            <span>
                                <strong>Bezorgen</strong>
                                <small><?= $wagenBezorgen['verzendkosten'] > 0 ? prijs($wagenBezorgen['verzendkosten']) : 'Gratis' ?> &middot; kamado's in regio Breda, we bellen voor een afspraak</small>
                            </span>
                        </label>
                        <label class="choice">
                            <input type="radio" name="levering" value="afhalen" <?= !$bezorgen ? 'checked' : '' ?> data-levering>
                            <span>
                                <strong>Afhalen in de winkel</strong>
                                <small>Gratis &middot; Voorbeeldstraat 12, Breda</small>
                            </span>
                        </label>
                    </div>

                    <div class="address" data-adres <?= !$bezorgen ? 'hidden' : '' ?>>
                        <div class="field-row field-row-address">
                            <div class="field <?= isset($fouten['postcode']) ? 'has-error' : '' ?>">
                                <label for="postcode">Postcode *</label>
                                <input type="text" name="postcode" id="postcode" value="<?= e($invoer['postcode']) ?>" maxlength="7" autocomplete="postal-code" <?= veldFout('postcode', $fouten) ?>>
                                <?= fout('postcode', $fouten) ?>
                            </div>
                            <div class="field <?= isset($fouten['huisnummer']) ? 'has-error' : '' ?>">
                                <label for="huisnummer">Huisnummer *</label>
                                <input type="text" name="huisnummer" id="huisnummer" value="<?= e($invoer['huisnummer']) ?>" maxlength="12" <?= veldFout('huisnummer', $fouten) ?>>
                                <?= fout('huisnummer', $fouten) ?>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field <?= isset($fouten['straat']) ? 'has-error' : '' ?>">
                                <label for="straat">Straat *</label>
                                <input type="text" name="straat" id="straat" value="<?= e($invoer['straat']) ?>" maxlength="100" autocomplete="address-line1" <?= veldFout('straat', $fouten) ?>>
                                <?= fout('straat', $fouten) ?>
                            </div>
                            <div class="field <?= isset($fouten['plaats']) ? 'has-error' : '' ?>">
                                <label for="plaats">Plaats *</label>
                                <input type="text" name="plaats" id="plaats" value="<?= e($invoer['plaats']) ?>" maxlength="60" autocomplete="address-level2" <?= veldFout('plaats', $fouten) ?>>
                                <?= fout('plaats', $fouten) ?>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form-section">
                    <legend>Betalen</legend>
                    <div class="choice-group">
                        <label class="choice">
                            <input type="radio" name="betaalwijze" value="online" <?= $invoer['betaalwijze'] !== 'afhalen' || $bezorgen ? 'checked' : '' ?>>
                            <span>
                                <strong>Nu online betalen</strong>
                                <small>iDEAL, Bancontact of creditcard</small>
                            </span>
                        </label>
                        <label class="choice" data-betaal-afhalen <?= $bezorgen ? 'hidden' : '' ?>>
                            <input type="radio" name="betaalwijze" value="afhalen" <?= $invoer['betaalwijze'] === 'afhalen' && !$bezorgen ? 'checked' : '' ?>>
                            <span>
                                <strong>Betalen bij afhalen</strong>
                                <small>Pin of contant in de winkel</small>
                            </span>
                        </label>
                    </div>
                </fieldset>

                <div class="field <?= isset($fouten['opmerkingen']) ? 'has-error' : '' ?>">
                    <label for="opmerkingen">Opmerkingen <small>(optioneel)</small></label>
                    <textarea name="opmerkingen" id="opmerkingen" rows="3" maxlength="500" <?= veldFout('opmerkingen', $fouten) ?>><?= e($invoer['opmerkingen']) ?></textarea>
                    <?= fout('opmerkingen', $fouten) ?>
                </div>

                <!-- Honeypot tegen spam: niet zichtbaar voor bezoekers -->
                <div class="hp" aria-hidden="true">
                    <label for="website">Laat dit veld leeg</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="field <?= isset($fouten['akkoord']) ? 'has-error' : '' ?>">
                    <label class="checkbox">
                        <input type="checkbox" name="akkoord" value="ja" <?= veldFout('akkoord', $fouten) ?>>
                        <span>Ik ga akkoord met de <a href="voorwaarden" target="_blank">voorwaarden</a> en dat Kamado Breda mijn gegevens gebruikt om deze bestelling te verwerken. *</span>
                    </label>
                    <?= fout('akkoord', $fouten) ?>
                </div>

                <button type="submit" class="btn btn-primary btn-large btn-block">
                    <span data-knop-online <?= $invoer['betaalwijze'] === 'afhalen' && !$bezorgen ? 'hidden' : '' ?>>Bestellen en betalen</span>
                    <span data-knop-afhalen <?= $invoer['betaalwijze'] === 'afhalen' && !$bezorgen ? '' : 'hidden' ?>>Bestelling plaatsen</span>
                </button>
                <p class="note">* verplicht veld &middot; Je kunt je bestelling binnen 14 dagen kosteloos annuleren.</p>
            </form>

            <aside class="summary">
                <h2>Je bestelling</h2>
                <ul class="summary-items">
                    <?php foreach ($wagen['regels'] as $regel): ?>
                        <li>
                            <span><?= $regel['aantal'] ?>&times; <?= e($regel['naam']) ?></span>
                            <span><?= prijs($regel['totaal']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <dl class="totals">
                    <dt>Subtotaal</dt>
                    <dd><?= prijs($wagen['subtotaal']) ?></dd>
                    <dt>Verzendkosten</dt>
                    <dd data-verzendkosten
                        data-bezorgen="<?= e($wagenBezorgen['verzendkosten'] > 0 ? prijs($wagenBezorgen['verzendkosten']) : 'Gratis') ?>"
                        data-afhalen="Gratis"><?= $wagen['verzendkosten'] > 0 ? prijs($wagen['verzendkosten']) : 'Gratis' ?></dd>
                </dl>
                <p class="summary-total">Totaal
                    <span data-totaal
                          data-bezorgen="<?= e(prijs($wagenBezorgen['totaal'])) ?>"
                          data-afhalen="<?= e(prijs($wagenBezorgen['subtotaal'])) ?>"><?= prijs($wagen['totaal']) ?></span>
                </p>
                <p class="note">Incl.
                    <span data-btw
                          data-bezorgen="<?= e(prijs($wagenBezorgen['btw'])) ?>"
                          data-afhalen="<?= e(prijs(round($wagenBezorgen['subtotaal'] / 121 * 21, 2))) ?>"><?= prijs($wagen['btw']) ?></span> btw
                </p>
                <a href="winkelwagen" class="link-arrow link-back">Winkelwagen aanpassen</a>
                <?php include __DIR__ . '/../includes/betaalmethodes.php'; ?>
            </aside>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
