<?php
require_once 'includes/data.php';

// Welke kamado? bijv. product.php?id=blue-l
$id = $_GET['id'] ?? '';

// Bestaat dit product niet? Dan een nette 404-melding.
if (!isset($kamados[$id])) {
    http_response_code(404);
    $paginaTitel = 'Product niet gevonden';
    $actief = 'kamados.php';
    require 'includes/header.php';
    ?>
    <section class="section">
        <div class="container narrow center">
            <h1>Oeps, deze kamado bestaat niet</h1>
            <p>Het product dat je zoekt is niet (meer) beschikbaar.</p>
            <a href="kamados.php" class="btn btn-primary">Bekijk alle kamado's</a>
        </div>
    </section>
    <?php
    require 'includes/footer.php';
    exit;
}

$product = $kamados[$id];
$galerij = $product['galerij'];

$paginaTitel = $product['naam'];
$actief = 'kamados.php';
$scripts = ['gallery.js'];
require 'includes/header.php';
?>

<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="Kruimelpad">
            <a href="index.php">Home</a> /
            <a href="kamados.php">Kamado's</a> /
            <span><?= e($product['naam']) ?></span>
        </nav>

        <div class="product-layout">
            <div>
                <?php include 'includes/gallery.php'; ?>
            </div>

            <div class="product-info">
                <span class="badge badge-inline"><?= e($product['badge']) ?></span>
                <h1><?= e($product['naam']) ?></h1>
                <p class="price price-big"><?= prijs($product['prijs']) ?></p>
                <p class="lead"><?= e($product['kort']) ?></p>
                <p><?= e($product['tekst']) ?></p>

                <ul class="check-list">
                    <?php foreach ($product['kenmerken'] as $kenmerk): ?>
                        <li><?= e($kenmerk) ?></li>
                    <?php endforeach; ?>
                </ul>

                <div class="product-actions">
                    <a href="mailto:info@kamadobreda.nl?subject=Interesse in <?= rawurlencode($product['naam']) ?>" class="btn btn-primary">Vraag offerte aan</a>
                    <a href="masterclass.php" class="btn btn-outline">Eerst een masterclass?</a>
                </div>
                <p class="note">Op voorraad in de winkel &middot; Bezorging in regio Breda mogelijk</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container product-bottom">
        <div>
            <h2>Specificaties</h2>
            <table class="specs">
                <?php foreach ($product['specs'] as $naam => $waarde): ?>
                    <tr>
                        <th scope="row"><?= e($naam) ?></th>
                        <td><?= e($waarde) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div>
            <h2>Andere kamado's</h2>
            <div class="grid grid-2">
                <?php
                $huidigId = $id;
                foreach ($kamados as $id => $kamado) {
                    if ($id === $huidigId) {
                        continue; // het product dat je nu bekijkt niet nog een keer tonen
                    }
                    include 'includes/kamado-kaart.php';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
