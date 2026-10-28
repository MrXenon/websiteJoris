<?php
defined('KAMADO_BREDA') || exit;

// De inhoud staat in het CMS: Beheer > Front-end page content, pagina "kamados".
// Daar staan ook de kamado's en accessoires zelf (layouts kamado-kamados en kamado-accessoires).
$cmsPagina = 'kamados';
$paginaTitel = "Kamado's & accessoires";
$beschrijving = "Bestel je blauwe kamado (M, L of XL) en gietijzeren accessoires online bij Kamado Breda. Bezorging in regio Breda of ophalen in de winkel.";
$actief = 'kamados';
$eersteKamado = reset($kamados);
if ($eersteKamado && $eersteKamado['foto'] !== '') {
    $ogFoto = cmsFotoPad($eersteKamado['foto']);
}

require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/cms-secties.php';
require __DIR__ . '/../includes/footer.php';
