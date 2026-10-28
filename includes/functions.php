<?php
defined('KAMADO_BREDA') || exit;

// Kleine hulpfuncties die ik op meerdere pagina's gebruik.

// Tekst veilig tonen in HTML (tegen XSS: <script> wordt gewoon tekst)
function e($tekst)
{
    return htmlspecialchars((string) $tekst, ENT_QUOTES, 'UTF-8');
}

// Nette link naar een pagina, relatief aan <base href>.
// url('kamados') -> "kamados", url('') -> "./" (de homepage)
function url($route = '')
{
    return $route === '' ? './' : $route;
}

// Volledig adres van de site, bijv. "https://www.kamadobreda.nl/"
function siteUrl($route = '')
{
    global $config;
    if ($config['site_url'] !== '') {
        $basis = rtrim($config['site_url'], '/') . '/';
    } else {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $basis = ($https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . BASIS;
    }
    return $basis . $route;
}

// Doorsturen naar een andere pagina en stoppen
function stuurDoor($route, $status = 303)
{
    $route = preg_replace('#^\./#', '', $route);
    header('Location: ' . BASIS . ltrim($route, '/'), true, $status);
    exit;
}

// CSS/JS-bestand met versienummer, zodat de browser het lang mag cachen
// maar wel de nieuwe versie ophaalt als ik het bestand aanpas.
function asset($bestand)
{
    $pad = __DIR__ . '/../' . $bestand;
    return $bestand . (file_exists($pad) ? '?v=' . filemtime($pad) : '');
}

// Foto tonen als <picture>: WebP voor moderne browsers, JPG als reserve.
// Breedte en hoogte erbij, zodat de pagina niet verspringt tijdens het laden.
function foto($bestand, $alt, $extra = [])
{
    $jpg = 'images/' . $bestand;
    $webp = preg_replace('/\.jpg$/', '.webp', $jpg);
    $maten = @getimagesize(__DIR__ . '/../' . $jpg);

    $attributen = [
        'src'      => $jpg,
        'alt'      => $alt,
        'width'    => $maten[0] ?? null,
        'height'   => $maten[1] ?? null,
        'loading'  => 'lazy',
        'decoding' => 'async',
    ];
    $attributen = array_merge($attributen, $extra);

    $html = '<picture>';
    if (file_exists(__DIR__ . '/../' . $webp)) {
        $html .= '<source srcset="' . e($webp) . '" type="image/webp">';
    }
    $html .= '<img';
    foreach ($attributen as $naam => $waarde) {
        if ($waarde !== null && $waarde !== false) {
            $html .= ' ' . $naam . '="' . e($waarde) . '"';
        }
    }
    return $html . '></picture>';
}

// Waarde veilig maken voor een CSV die in Excel geopend wordt.
// Begint iets met = + - of @ dan kan Excel het als formule uitvoeren,
// daarom zet ik er een ' voor.
function csvVeilig($waarde)
{
    $waarde = (string) $waarde;
    if ($waarde !== '' && in_array($waarde[0], ['=', '+', '-', '@'])) {
        return "'" . $waarde;
    }
    return $waarde;
}

// 1299 -> "€ 1.299,-" en 6.95 -> "€ 6,95"
function prijs($bedrag)
{
    $bedrag = round($bedrag, 2);
    if ($bedrag == floor($bedrag)) {
        return '€ ' . number_format($bedrag, 0, ',', '.') . ',-';
    }
    return '€ ' . number_format($bedrag, 2, ',', '.');
}

// "2026-10-24" -> "zaterdag 24 oktober 2026"
function datumNL($datum)
{
    $dagen = ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'];
    $maanden = ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli',
                'augustus', 'september', 'oktober', 'november', 'december'];

    $tijd = strtotime($datum);
    return $dagen[date('w', $tijd)] . ' ' . date('j', $tijd) . ' '
        . $maanden[date('n', $tijd) - 1] . ' ' . date('Y', $tijd);
}

// Korte datum voor op een kaartje: ["24", "okt"]
function datumKort($datum)
{
    $maanden = ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'];
    $tijd = strtotime($datum);
    return [date('j', $tijd), $maanden[date('n', $tijd) - 1]];
}

// ---------- Beveiliging van formulieren (CSRF) ----------
// Elk formulier krijgt een geheime code uit de sessie mee.
// Een andere website kent die code niet en kan dus niet namens de bezoeker iets versturen.
function csrfToken()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrfVeld()
{
    return '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}

function csrfOk()
{
    return isset($_POST['csrf']) && hash_equals(csrfToken(), (string) $_POST['csrf']);
}

// ---------- Korte meldingen na doorsturen ("flash") ----------
function zetMelding($tekst, $soort = 'succes')
{
    $_SESSION['melding'] = ['tekst' => $tekst, 'soort' => $soort];
}

function haalMelding()
{
    $melding = $_SESSION['melding'] ?? null;
    unset($_SESSION['melding']);
    return $melding;
}
