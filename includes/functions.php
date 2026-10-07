<?php
// Kleine hulpfuncties die ik op meerdere pagina's gebruik.

// Nederlandse tijd gebruiken (anders is het UTC, 1 of 2 uur te vroeg)
date_default_timezone_set('Europe/Amsterdam');

// Tekst veilig tonen in HTML (tegen XSS: <script> wordt gewoon tekst)
function e($tekst)
{
    return htmlspecialchars((string) $tekst, ENT_QUOTES, 'UTF-8');
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

// 1299 -> "€ 1.299,-"
function prijs($bedrag)
{
    return '€ ' . number_format($bedrag, 0, ',', '.') . ',-';
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
