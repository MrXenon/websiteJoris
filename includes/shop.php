<?php
defined('KAMADO_BREDA') || exit;

// Webshop: producten, winkelwagen, bestellingen en betalen via Mollie.
// De winkelwagen staat in de sessie: ['blue-l' => 1, 'skillet' => 2]
// Prijzen komen ALTIJD uit het CMS (via data.php), nooit uit het formulier (anders kan iemand zelf een prijs verzinnen).

// ---------- Producten ----------

// Alle bestelbare producten (kamado's + accessoires) in één lijst
function alleProducten()
{
    global $kamados, $accessoires;
    $producten = [];
    foreach ($kamados as $id => $kamado) {
        $producten[$id] = [
            'naam'  => $kamado['naam'],
            'prijs' => $kamado['prijs'],
            'foto'  => $kamado['foto'],
            'link'  => 'kamados/' . $id,
        ];
    }
    foreach ($accessoires as $id => $item) {
        $producten[$id] = [
            'naam'  => $item['naam'],
            'prijs' => $item['prijs'],
            'foto'  => $item['foto'],
            'link'  => 'kamados#accessoires',
        ];
    }
    return $producten;
}

// ---------- Winkelwagen ----------

function winkelwagen()
{
    return $_SESSION['winkelwagen'] ?? [];
}

// Totaal aantal artikelen (voor het bolletje bij het winkelwagen-icoon)
function aantalInWinkelwagen()
{
    return array_sum(winkelwagen());
}

// Aantal van een product instellen. 0 = verwijderen.
function zetInWinkelwagen($id, $aantal)
{
    global $config;
    if (!isset(alleProducten()[$id])) {
        return;
    }
    $aantal = max(0, min((int) $aantal, $config['max_aantal']));
    if ($aantal === 0) {
        unset($_SESSION['winkelwagen'][$id]);
    } else {
        $_SESSION['winkelwagen'][$id] = $aantal;
    }
}

function voegToeAanWinkelwagen($id, $aantal = 1)
{
    $huidig = winkelwagen()[$id] ?? 0;
    zetInWinkelwagen($id, $huidig + $aantal);
}

function leegWinkelwagen()
{
    unset($_SESSION['winkelwagen']);
}

// Regels + totalen berekenen. $bezorgen bepaalt of er verzendkosten bij komen.
function berekenWinkelwagen($bezorgen = false)
{
    global $config;
    $producten = alleProducten();
    $regels = [];
    $subtotaal = 0;

    foreach (winkelwagen() as $id => $aantal) {
        if (!isset($producten[$id])) {
            continue; // product bestaat niet meer
        }
        $product = $producten[$id];
        $regels[$id] = $product + [
            'id'     => $id,
            'aantal' => $aantal,
            'totaal' => $product['prijs'] * $aantal,
        ];
        $subtotaal += $product['prijs'] * $aantal;
    }

    $verzendkosten = 0;
    if ($bezorgen && $subtotaal < $config['gratis_verzending_vanaf']) {
        $verzendkosten = $config['verzendkosten'];
    }

    return [
        'regels'        => $regels,
        'subtotaal'     => $subtotaal,
        'verzendkosten' => $verzendkosten,
        'totaal'        => round($subtotaal + $verzendkosten, 2),
        // Btw zit in de prijs (21%): totaal / 121 * 21
        'btw'           => round(($subtotaal + $verzendkosten) / 121 * 21, 2),
    ];
}

// ---------- Bestellingen ----------
// Elke bestelling is een JSON-bestand in data/bestellingen/ (map is afgeschermd met .htaccess).

function bestellingPad($nummer)
{
    // Alleen hoofdletters, cijfers en streepjes: zo kan niemand "../" in een bestandsnaam stoppen
    if (!preg_match('/^KB-[0-9]{8}-[A-Z0-9]{6}$/', $nummer)) {
        return null;
    }
    return __DIR__ . '/../data/bestellingen/' . $nummer . '.json';
}

function nieuwBestelnummer()
{
    $tekens = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // geen 0/O en 1/I, die lijken op elkaar
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $tekens[random_int(0, strlen($tekens) - 1)];
    }
    return 'KB-' . date('Ymd') . '-' . $code;
}

function laadBestelling($nummer)
{
    $pad = bestellingPad($nummer);
    if ($pad === null || !file_exists($pad)) {
        return null;
    }
    return json_decode(file_get_contents($pad), true);
}

function bewaarBestelling($bestelling)
{
    $map = __DIR__ . '/../data/bestellingen';
    if (!is_dir($map)) {
        mkdir($map, 0775, true);
    }
    $bestelling['bijgewerkt'] = date('Y-m-d H:i:s');
    file_put_contents(
        bestellingPad($bestelling['nummer']),
        json_encode($bestelling, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

// Status wijzigen. Wordt een bestelling definitief (betaald of betalen bij afhalen),
// dan komt hij één keer in data/bestellingen.csv, zodat Joris alles in Excel kan openen.
function zetStatus(&$bestelling, $status)
{
    $definitief = ['betaald', 'betalen-bij-afhalen'];
    $wasDefinitief = in_array($bestelling['status'], $definitief);
    $bestelling['status'] = $status;

    if (in_array($status, $definitief) && !$wasDefinitief) {
        schrijfBestellingNaarCsv($bestelling);
        mailWinkel($bestelling);
    }
    bewaarBestelling($bestelling);
}

function schrijfBestellingNaarCsv($bestelling)
{
    $artikelen = [];
    foreach ($bestelling['regels'] as $regel) {
        $artikelen[] = $regel['aantal'] . 'x ' . $regel['naam'];
    }
    $k = $bestelling['klant'];
    $rij = [
        'datum'      => $bestelling['aangemaakt'],
        'nummer'     => $bestelling['nummer'],
        'status'     => $bestelling['status'] . (!empty($bestelling['test']) ? ' (test)' : ''),
        'naam'       => $k['voornaam'] . ' ' . $k['achternaam'],
        'email'      => $k['email'],
        'telefoon'   => $k['telefoon'],
        'levering'   => $bestelling['levering'],
        'adres'      => $bestelling['levering'] === 'bezorgen'
                        ? $k['straat'] . ' ' . $k['huisnummer'] . ', ' . $k['postcode'] . ' ' . $k['plaats'] : '',
        'artikelen'  => implode(' | ', $artikelen),
        'totaal'     => number_format($bestelling['totaal'], 2, ',', ''),
        'opmerkingen' => $k['opmerkingen'],
    ];

    $bestand = __DIR__ . '/../data/bestellingen.csv';
    $nieuw = !file_exists($bestand);
    $handle = fopen($bestand, 'a');
    if ($handle) {
        if ($nieuw) {
            fwrite($handle, "\xEF\xBB\xBF"); // BOM, zodat Excel é en – goed laat zien
            fputcsv($handle, array_keys($rij), ';', '"', '');
        }
        fputcsv($handle, array_map('csvVeilig', $rij), ';', '"', '');
        fclose($handle);
    }
}

// Korte melding naar de winkel. Lukt mailen niet (bijv. lokaal), dan is dat geen probleem:
// de bestelling staat ook in data/bestellingen.csv.
function mailWinkel($bestelling)
{
    global $config;
    $tekst = "Nieuwe bestelling {$bestelling['nummer']} ({$bestelling['status']})\n\n";
    foreach ($bestelling['regels'] as $regel) {
        $tekst .= "{$regel['aantal']}x {$regel['naam']}\n";
    }
    $tekst .= "\nTotaal: " . prijs($bestelling['totaal']) . "\n";
    $tekst .= 'Klant: ' . $bestelling['klant']['voornaam'] . ' ' . $bestelling['klant']['achternaam']
            . ' <' . $bestelling['klant']['email'] . ">\n";
    @mail($config['winkel_email'], 'Nieuwe bestelling ' . $bestelling['nummer'], $tekst);
}

// ---------- Mollie (online betalen) ----------
// Documentatie: https://docs.mollie.com/reference/create-payment

function mollieActief()
{
    global $config;
    return $config['mollie_api_key'] !== '';
}

// Verzoek naar de Mollie API. Geeft het antwoord als array, of null bij een fout.
function mollie($methode, $pad, $gegevens = null)
{
    global $config;
    $curl = curl_init('https://api.mollie.com/v2/' . $pad);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $methode,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $config['mollie_api_key'],
            'Content-Type: application/json',
        ],
    ]);
    if ($gegevens !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($gegevens));
    }
    $antwoord = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($antwoord === false || $status >= 400) {
        error_log('Mollie fout (' . $status . '): ' . $antwoord);
        return null;
    }
    return json_decode($antwoord, true);
}

// Betaling aanmaken. Geeft de URL waar de klant naartoe moet om te betalen.
function maakMollieBetaling(&$bestelling)
{
    $gegevens = [
        'amount'      => ['currency' => 'EUR', 'value' => number_format($bestelling['totaal'], 2, '.', '')],
        'description' => 'Kamado Breda bestelling ' . $bestelling['nummer'],
        'redirectUrl' => siteUrl('bestelling/' . $bestelling['nummer']),
        'metadata'    => ['bestelnummer' => $bestelling['nummer']],
        'locale'      => 'nl_NL',
    ];
    // Mollie kan een webhook niet op localhost bereiken, dan laten we hem weg.
    // De status wordt dan opgehaald als de klant terugkomt op de bestelpagina.
    $webhook = siteUrl('mollie-webhook');
    if (!preg_match('#//(localhost|127\.|[^/]+\.test)#', $webhook)) {
        $gegevens['webhookUrl'] = $webhook;
    }

    $betaling = mollie('POST', 'payments', $gegevens);
    if ($betaling === null) {
        return null;
    }
    $bestelling['mollie_id'] = $betaling['id'];
    bewaarBestelling($bestelling);
    return $betaling['_links']['checkout']['href'];
}

// Status van de betaling bij Mollie ophalen en de bestelling bijwerken
function werkMollieStatusBij(&$bestelling)
{
    if (empty($bestelling['mollie_id']) || !mollieActief()) {
        return;
    }
    $betaling = mollie('GET', 'payments/' . rawurlencode($bestelling['mollie_id']));
    if ($betaling === null) {
        return;
    }
    $statussen = [
        'paid'     => 'betaald',
        'open'     => 'open',
        'pending'  => 'open',
        'canceled' => 'geannuleerd',
        'expired'  => 'verlopen',
        'failed'   => 'mislukt',
    ];
    $nieuw = $statussen[$betaling['status']] ?? 'open';
    if ($nieuw !== $bestelling['status']) {
        zetStatus($bestelling, $nieuw);
    }
}
