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

// De inhoud staat in het CMS: Beheer > Front-end page content, pagina "masterclasses".
// Daar staan ook de masterclasses zelf (layout kamado-masterclasses) en de teksten van het formulier (kamado-inschrijven).
$cmsPagina = 'masterclasses';
$paginaTitel = 'Masterclasses';
$beschrijving = 'Leer low & slow barbecueën op de kamado bij Kamado Breda. Bekijk de agenda van de BBQ-masterclasses en schrijf je direct in.';
$actief = 'masterclasses';

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/cms-secties.php';
require __DIR__ . '/../includes/footer.php';
