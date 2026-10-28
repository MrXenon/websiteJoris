<?php
defined('KAMADO_BREDA') || exit;

// De producten, masterclasses en fotobronnen komen uit het CMS (zie includes/cms.php).
// Ze staan in de blokken met de layouts kamado-kamados, kamado-accessoires, kamado-masterclasses,
// kamado-inschrijven en kamado-fotobronnen. Hier worden ze omgezet naar de lijsten die de pagina's gebruiken.

// Product-ID's staan in de URL en de winkelwagen: alleen kleine letters, cijfers en -
function geldigId($id)
{
    return (bool) preg_match('/^[a-z0-9][a-z0-9-]{0,40}$/', $id);
}

// ---------- Kamado's ----------
$kamadoBlok = cmsBlokMetLayout('kamado-kamados');
$kamados = [];
foreach (cmsLijst($kamadoBlok, 'producten') as $product) {
    $id = trim((string) ($product['id'] ?? ''));
    if (!geldigId($id) || cmsBedrag($product['prijs'] ?? '') <= 0) {
        continue;
    }
    $naam = trim((string) ($product['naam'] ?? ''));
    $foto = trim((string) ($product['foto'] ?? ''));

    // Galerij: "bestand | omschrijving" per regel, zonder regels alleen de hoofdfoto
    $galerij = cmsParen($product['galerij'] ?? '');
    if (!$galerij && $foto !== '') {
        $galerij = [[$foto, $naam]];
    }

    $specs = [];
    foreach (cmsParen($product['specs'] ?? '') as [$specNaam, $specWaarde]) {
        $specs[$specNaam] = $specWaarde;
    }

    $kamados[$id] = [
        'naam'      => $naam,
        'kort'      => trim((string) ($product['kort'] ?? '')),
        'prijs'     => cmsBedrag($product['prijs']),
        'badge'     => trim((string) ($product['badge'] ?? '')),
        'foto'      => $foto,
        'tekst'     => trim((string) ($product['tekst'] ?? '')),
        'kenmerken' => cmsRegels($product['kenmerken'] ?? ''),
        'specs'     => $specs,
        'galerij'   => $galerij,
    ];
}

// Voordelen in het bestelblok op de productpagina
$koopVoordelen = cmsRegels(cmsVeld($kamadoBlok, 'voordelen'));

// ---------- Accessoires ----------
// De sleutel (bijv. 'skillet') is het product-ID in de winkelwagen.
$accessoires = [];
foreach (cmsLijst(cmsBlokMetLayout('kamado-accessoires'), 'producten') as $item) {
    $id = trim((string) ($item['id'] ?? ''));
    if (!geldigId($id) || isset($kamados[$id]) || cmsBedrag($item['prijs'] ?? '') <= 0) {
        continue;
    }
    $accessoires[$id] = [
        'naam'  => trim((string) ($item['naam'] ?? '')),
        'prijs' => cmsBedrag($item['prijs']),
        'foto'  => trim((string) ($item['foto'] ?? '')),
        'tekst' => trim((string) ($item['tekst'] ?? '')),
    ];
}

// ---------- Masterclasses ----------
$masterclasses = [];
foreach (cmsLijst(cmsBlokMetLayout('kamado-masterclasses'), 'masterclasses') as $class) {
    $id = trim((string) ($class['id'] ?? ''));
    $datum = trim((string) ($class['datum'] ?? ''));
    if (!geldigId($id) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datum)) {
        continue;
    }
    $masterclasses[$id] = [
        'titel'   => trim((string) ($class['titel'] ?? '')),
        'datum'   => $datum,
        'tijd'    => trim((string) ($class['tijd'] ?? '')),
        'prijs'   => cmsBedrag($class['prijs'] ?? ''),
        'plekken' => max(0, (int) ($class['plekken'] ?? 0)),
        'niveau'  => trim((string) ($class['niveau'] ?? '')),
        'foto'    => trim((string) ($class['foto'] ?? '')),
        'tekst'   => trim((string) ($class['tekst'] ?? '')),
    ];
}

// Op datum, en masterclasses die al geweest zijn niet meer tonen (en niet meer laten boeken)
uasort($masterclasses, function ($a, $b) {
    return strcmp($a['datum'], $b['datum']);
});
$masterclasses = array_filter($masterclasses, function ($class) {
    return $class['datum'] >= date('Y-m-d');
});

// Maximaal aantal personen per inschrijving
$maxPersonen = max(1, (int) cmsVeld(cmsBlokMetLayout('kamado-inschrijven'), 'maxPersonen', 4));

// ---------- Fotobronnen (licenties) ----------
$fotobronnen = [];
foreach (cmsLijst(cmsBlokMetLayout('kamado-fotobronnen'), 'bronnen') as $bron) {
    $fotobronnen[] = [
        'bestand'  => trim((string) ($bron['bestand'] ?? '')),
        'maker'    => trim((string) ($bron['maker'] ?? '')),
        'licentie' => trim((string) ($bron['licentie'] ?? '')),
        'url'      => trim((string) ($bron['url'] ?? '')),
    ];
}
