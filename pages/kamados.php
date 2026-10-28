<?php
$paginaTitel = "Kamado's";
$actief = 'kamados.php';
$scripts = ['gallery.js'];
require 'includes/header.php';

// Fotos voor de grote galerij bovenaan
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

<section class="section">
    <div class="container">
        <?php include 'includes/gallery.php'; ?>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <div class="section-head">
            <h2>Kies jouw formaat</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam porta sem malesuada magna mollis euismod.</p>
        </div>
        <div class="grid grid-3">
            <?php foreach ($kamados as $id => $kamado) {
                include 'includes/kamado-kaart.php';
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
            <?php foreach ($accessoires as $item): ?>
                <article class="card">
                    <div class="card-image">
                        <img src="images/thumbs/<?= e($item['foto']) ?>" alt="<?= e($item['naam']) ?>" loading="lazy">
                    </div>
                    <div class="card-body">
                        <h3><?= e($item['naam']) ?></h3>
                        <p><?= e($item['tekst']) ?></p>
                        <p class="price"><?= prijs($item['prijs']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="note">Accessoires zijn verkrijgbaar in de winkel aan de Voorbeeldstraat in Breda.</p>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
