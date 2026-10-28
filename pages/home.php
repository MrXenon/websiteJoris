<?php
defined('KAMADO_BREDA') || exit;

// De inhoud staat in het CMS: Beheer > Front-end page content, pagina "index"
$cmsPagina = 'index';
$paginaTitel = 'Blauwe kamado\'s & BBQ-masterclasses';
$actief = '';

// De foto van de hero alvast laden (die is meteen in beeld)
$hero = null;
foreach (cmsBlokken($cmsPagina) as $blok) {
    if ($blok->getFplayout() === 'kamado-hero') {
        $hero = $blok;
        break;
    }
}
$heroFoto = $hero ? (cmsFotoUrl($hero->getFpimage(), true) ?: cmsFotoUrl($hero->getFpimage())) : '';
if ($heroFoto !== '') {
    $preloadFoto = substr($heroFoto, strlen('images/'));
}

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/cms-secties.php';
require __DIR__ . '/../includes/footer.php';
