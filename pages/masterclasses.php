<?php
defined('KAMADO_BREDA') || exit;

$fouten = [];

// Standaardwaarden van het formulier (leeg, of de masterclass uit de link ?class=...)
$invoer = [
    'masterclass' => $_GET['class'] ?? '',
    'voornaam'    => '',
    'achternaam'  => '',
    'email'       => '',
    'telefoon'    => '',
    'personen'    => '1',
    'ervaring'    => '',
    'wensen'      => '',
    'akkoord'     => '',
];

// ---------- Formulier verwerken ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Alle velden ophalen uit $_POST en spaties weghalen
    foreach ($invoer as $veld => $waarde) {
        $invoer[$veld] = trim($_POST[$veld] ?? '');
    }

    // Honeypot en CSRF: alleen spambots en andere websites gaan hier fout
    if (!empty($_POST['website']) || !csrfOk()) {
        $fouten['algemeen'] = 'Er ging iets mis, probeer het opnieuw.';
    }

    // Controleren
    if (!isset($masterclasses[$invoer['masterclass']])) {
        $fouten['masterclass'] = 'Kies een masterclass.';
    }
    if ($invoer['voornaam'] === '') {
        $fouten['voornaam'] = 'Vul je voornaam in.';
    } elseif (strlen($invoer['voornaam']) > 50) {
        $fouten['voornaam'] = 'Je voornaam is te lang.';
    }
    if ($invoer['achternaam'] === '') {
        $fouten['achternaam'] = 'Vul je achternaam in.';
    } elseif (strlen($invoer['achternaam']) > 80) {
        $fouten['achternaam'] = 'Je achternaam is te lang.';
    }
    if (!filter_var($invoer['email'], FILTER_VALIDATE_EMAIL)) {
        $fouten['email'] = 'Vul een geldig e-mailadres in.';
    }
    if ($invoer['telefoon'] !== '' && !preg_match('/^[0-9 +()-]{10,15}$/', $invoer['telefoon'])) {
        $fouten['telefoon'] = 'Vul een geldig telefoonnummer in (of laat het leeg).';
    }
    $personen = (int) $invoer['personen'];
    if ($personen < 1 || $personen > $maxPersonen) {
        $fouten['personen'] = "Kies 1 tot $maxPersonen personen.";
    } elseif (isset($masterclasses[$invoer['masterclass']])
              && $personen > $masterclasses[$invoer['masterclass']]['plekken']) {
        $fouten['personen'] = 'Er zijn niet genoeg plekken meer voor dit aantal personen.';
    }
    if (!in_array($invoer['ervaring'], ['geen', 'beetje', 'veel'])) {
        $fouten['ervaring'] = 'Geef aan hoeveel ervaring je hebt.';
    }
    if (strlen($invoer['wensen']) > 500) {
        $fouten['wensen'] = 'Maximaal 500 tekens.';
    }
    if ($invoer['akkoord'] !== 'ja') {
        $fouten['akkoord'] = 'Je moet akkoord gaan om je in te schrijven.';
    }

    // Geen fouten? Opslaan en door naar de bevestigingspagina
    if (empty($fouten)) {
        $class = $masterclasses[$invoer['masterclass']];

        $aanmelding = [
            'datum_inschrijving' => date('Y-m-d H:i'),
            'masterclass'        => $class['titel'],
            'datum'              => $class['datum'],
            'tijd'               => $class['tijd'],
            'voornaam'           => $invoer['voornaam'],
            'achternaam'         => $invoer['achternaam'],
            'email'              => $invoer['email'],
            'telefoon'           => $invoer['telefoon'],
            'personen'           => $personen,
            'ervaring'           => $invoer['ervaring'],
            'wensen'             => $invoer['wensen'],
            'totaal'             => $personen * $class['prijs'],
        ];

        // Opslaan in een CSV-bestand (map data/ is afgeschermd met .htaccess)
        $bestand = __DIR__ . '/../data/aanmeldingen.csv';
        $nieuw = !file_exists($bestand);
        $handle = fopen($bestand, 'a');
        if ($handle) {
            if ($nieuw) {
                fwrite($handle, "\xEF\xBB\xBF"); // BOM, zodat Excel é en – goed laat zien
                fputcsv($handle, array_keys($aanmelding), ';', '"', '');
            }
            fputcsv($handle, array_map('csvVeilig', $aanmelding), ';', '"', '');
            fclose($handle);
        }

        // Gegevens meegeven aan de bevestigingspagina via de sessie
        $_SESSION['aanmelding'] = $aanmelding;
        stuurDoor('bevestiging');
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

$paginaTitel = 'Masterclasses';
$beschrijving = 'Leer low & slow barbecueën op de kamado bij Kamado Breda. Bekijk de agenda van de BBQ-masterclasses en schrijf je direct in.';
$actief = 'masterclasses';
$scripts = ['form.js'];
require __DIR__ . '/../includes/header.php';
?>

<section class="page-hero page-hero-image" style="background-image: image-set(url('images/masterclass-brisket-folie.webp') type('image/webp'), url('images/masterclass-brisket-folie.jpg') type('image/jpeg'))">
    <div class="container">
        <p class="eyebrow">Leer het van Joris</p>
        <h1>BBQ-masterclasses</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Agenda</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Alle masterclasses vinden plaats in de winkel en op het terras in Breda.</p>
        </div>
        <div class="grid grid-3">
            <?php foreach ($masterclasses as $id => $class) {
                include __DIR__ . '/../includes/masterclass-kaart.php';
            } ?>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container split">
        <div class="split-text">
            <p class="eyebrow">Wat kun je verwachten?</p>
            <h2>Een dag vol vuur en vlees</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ullamcorper nulla non metus auctor fringilla. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
            <ul class="check-list">
                <li>Lorem ipsum dolor sit amet</li>
                <li>Consectetur adipiscing elit</li>
                <li>Inclusief eten en drinken</li>
                <li>Recepten om mee naar huis te nemen</li>
            </ul>
        </div>
        <div class="split-image">
            <?= foto('masterclass-bbq-borden.jpg', 'Borden met brisket, worst en bijgerechten') ?>
        </div>
    </div>
</section>

<section class="section" id="inschrijven">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Doe mee</p>
            <h2>Inschrijven</h2>
            <p>Vul het formulier in. Je krijgt direct een bevestiging te zien.</p>
        </div>

        <div class="form-layout">
            <form action="masterclasses#inschrijven" method="post" class="form" id="inschrijfformulier" novalidate>
                <?= csrfVeld() ?>

                <?php if (!empty($fouten)): ?>
                    <div class="alert alert-error" role="alert">
                        <strong>Niet alles is goed ingevuld.</strong> Controleer de rode velden hieronder.
                        <?= isset($fouten['algemeen']) ? '<br>' . e($fouten['algemeen']) : '' ?>
                    </div>
                <?php endif; ?>

                <div class="field <?= isset($fouten['masterclass']) ? 'has-error' : '' ?>">
                    <label for="masterclass">Masterclass *</label>
                    <select name="masterclass" id="masterclass" required>
                        <option value="">-- Kies een masterclass --</option>
                        <?php foreach ($masterclasses as $id => $class): ?>
                            <option value="<?= e($id) ?>"
                                    data-prijs="<?= $class['prijs'] ?>"
                                    data-plekken="<?= $class['plekken'] ?>"
                                    data-datum="<?= e(datumNL($class['datum'])) ?>"
                                    <?= $invoer['masterclass'] === $id ? 'selected' : '' ?>>
                                <?= e($class['titel']) ?> – <?= datumNL($class['datum']) ?> (<?= prijs($class['prijs']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= fout('masterclass', $fouten) ?>
                </div>

                <div class="field-row">
                    <div class="field <?= isset($fouten['voornaam']) ? 'has-error' : '' ?>">
                        <label for="voornaam">Voornaam *</label>
                        <input type="text" name="voornaam" id="voornaam" value="<?= e($invoer['voornaam']) ?>" maxlength="50" autocomplete="given-name" required>
                        <?= fout('voornaam', $fouten) ?>
                    </div>
                    <div class="field <?= isset($fouten['achternaam']) ? 'has-error' : '' ?>">
                        <label for="achternaam">Achternaam *</label>
                        <input type="text" name="achternaam" id="achternaam" value="<?= e($invoer['achternaam']) ?>" maxlength="80" autocomplete="family-name" required>
                        <?= fout('achternaam', $fouten) ?>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field <?= isset($fouten['email']) ? 'has-error' : '' ?>">
                        <label for="email">E-mailadres *</label>
                        <input type="email" name="email" id="email" value="<?= e($invoer['email']) ?>" autocomplete="email" required>
                        <?= fout('email', $fouten) ?>
                    </div>
                    <div class="field <?= isset($fouten['telefoon']) ? 'has-error' : '' ?>">
                        <label for="telefoon">Telefoon <small>(optioneel)</small></label>
                        <input type="tel" name="telefoon" id="telefoon" value="<?= e($invoer['telefoon']) ?>" autocomplete="tel">
                        <?= fout('telefoon', $fouten) ?>
                    </div>
                </div>

                <div class="field <?= isset($fouten['personen']) ? 'has-error' : '' ?>">
                    <label for="personen">Aantal personen *</label>
                    <select name="personen" id="personen">
                        <?php for ($i = 1; $i <= $maxPersonen; $i++): ?>
                            <option value="<?= $i ?>" <?= (int) $invoer['personen'] === $i ? 'selected' : '' ?>>
                                <?= $i ?> <?= $i === 1 ? 'persoon' : 'personen' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <?= fout('personen', $fouten) ?>
                </div>

                <fieldset class="field <?= isset($fouten['ervaring']) ? 'has-error' : '' ?>">
                    <legend>Hoeveel ervaring heb je met een kamado? *</legend>
                    <div class="radio-group">
                        <?php foreach (['geen' => 'Geen', 'beetje' => 'Een beetje', 'veel' => 'Veel'] as $waarde => $tekst): ?>
                            <label class="radio">
                                <input type="radio" name="ervaring" value="<?= $waarde ?>" <?= $invoer['ervaring'] === $waarde ? 'checked' : '' ?>>
                                <?= $tekst ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?= fout('ervaring', $fouten) ?>
                </fieldset>

                <div class="field <?= isset($fouten['wensen']) ? 'has-error' : '' ?>">
                    <label for="wensen">Dieetwensen of opmerkingen <small>(optioneel)</small></label>
                    <textarea name="wensen" id="wensen" rows="4" maxlength="500"><?= e($invoer['wensen']) ?></textarea>
                    <p class="char-count"><span id="tekens">0</span> / 500 tekens</p>
                    <?= fout('wensen', $fouten) ?>
                </div>

                <!-- Honeypot tegen spam: niet zichtbaar voor bezoekers -->
                <div class="hp" aria-hidden="true">
                    <label for="website">Laat dit veld leeg</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="field <?= isset($fouten['akkoord']) ? 'has-error' : '' ?>">
                    <label class="checkbox">
                        <input type="checkbox" name="akkoord" value="ja" <?= $invoer['akkoord'] === 'ja' ? 'checked' : '' ?>>
                        Ik ga akkoord dat Kamado Breda mijn gegevens gebruikt om deze inschrijving te verwerken. *
                    </label>
                    <?= fout('akkoord', $fouten) ?>
                </div>

                <button type="submit" class="btn btn-primary btn-large">Inschrijven</button>
                <p class="note">* verplicht veld</p>
            </form>

            <aside class="summary" aria-live="polite">
                <h3>Jouw inschrijving</h3>
                <dl>
                    <dt>Masterclass</dt>
                    <dd id="sum-class">Nog niet gekozen</dd>
                    <dt>Datum</dt>
                    <dd id="sum-datum">–</dd>
                    <dt>Aantal personen</dt>
                    <dd id="sum-personen">1</dd>
                </dl>
                <p class="summary-total">Totaal <span id="sum-totaal">€ 0,-</span></p>
                <p class="note" id="sum-plekken"></p>
            </aside>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
