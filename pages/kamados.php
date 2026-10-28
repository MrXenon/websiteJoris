<?php
defined('KAMADO_BREDA') || exit;

$paginaTitel = "Kamado's & accessoires";
$beschrijving = "Bestel je blauwe kamado (M, L of XL) en gietijzeren accessoires online bij Kamado Breda. Bezorging in regio Breda of ophalen in de winkel.";
$actief = 'kamados';
$scripts = ['gallery.js'];
$ogFoto = 'kamado-blue-l.jpg';
require __DIR__ . '/../includes/header.php';

// Foto's voor de grote galerij bovenaan
$galerij = [
    ['kamado-blue-l.jpg', 'Kamado Breda Blue L met houten zijtafels'],
    ['kamado-blue-xl.jpg', 'Kamado Breda Blue XL'],
    ['kamado-blue-m.jpg', 'Kamado Breda Blue M'],
    ['detail-thermometer.jpg', 'Thermometer op het blauwe deksel'],
    ['detail-deksel-handvat.jpg', 'Houten handvat met Kamado Breda logo'],
    ['actie-ribs.jpg', 'Spareribs op het rooster van een kamado'],
    ['actie-kipspiesjes.jpg', 'Kipspiesjes boven gloeiende kolen'],
    ['winkel-gietijzer-schap.jpg', 'Schap met gietijzeren pannen in de winkel'],
];
?>

<section class="page-hero">
    <div class="container">
        <p class="eyebrow">Het assortiment</p>
        <h1>Onze blauwe kamado's</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
    </div>
</section>

<section class="section section-tight">
    <div class="container">
        <?php include __DIR__ . '/../includes/gallery.php'; ?>
    </div>
</section>

<section class="section section-light" id="kamados">
    <div class="container">
        <div class="section-head">
            <h2>Kies jouw formaat</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam porta sem malesuada magna mollis euismod.</p>
        </div>
        <div class="grid grid-3">
            <?php foreach ($kamados as $id => $kamado) {
                include __DIR__ . '/../includes/kamado-kaart.php';
            } ?>
        </div>
    </div>
</section>

<section class="section" id="accessoires">
    <div class="container">
        <div class="section-head">
            <p class="eyebrow">Maak het compleet</p>
            <h2>Gietijzer &amp; accessoires</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper.</p>
        </div>
        <div class="grid grid-4">
            <?php foreach ($accessoires as $id => $item) {
                include __DIR__ . '/../includes/accessoire-kaart.php';
            } ?>
        </div>
        <p class="note">Bestel online en laat het bezorgen, of haal je bestelling gratis op in de winkel aan de Voorbeeldstraat in Breda.</p>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
