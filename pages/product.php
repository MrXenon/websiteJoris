<?php
defined('KAMADO_BREDA') || exit;

// Welke kamado? Komt uit de URL, bijv. /kamados/blue-l (zie index.php)
$productId = $parameter ?? '';

// Bestaat dit product niet? Dan een nette 404-melding.
if (!isset($kamados[$productId])) {
    http_response_code(404);
    $paginaTitel = 'Product niet gevonden';
    $actief = 'kamados';
    $geenIndex = true;
    require __DIR__ . '/../includes/header.php';
    ?>
    <section class="section">
        <div class="container narrow center">
            <h1>Oeps, deze kamado bestaat niet</h1>
            <p>Het product dat je zoekt is niet (meer) beschikbaar.</p>
            <a href="kamados" class="btn btn-primary">Bekijk alle kamado's</a>
        </div>
    </section>
    <?php
    require __DIR__ . '/../includes/footer.php';
    return;
}

$product = $kamados[$productId];
$galerij = $product['galerij'];

$paginaTitel = $product['naam'];
$beschrijving = $product['naam'] . ' – ' . $product['kort'] . ' Nu online te bestellen voor ' . prijs($product['prijs']) . ' bij Kamado Breda.';
$actief = 'kamados';
$scripts = ['gallery.js'];
$ogFoto = $product['foto'];

// Gestructureerde gegevens voor Google (prijs en voorraad in de zoekresultaten)
$schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => $product['naam'],
    'description' => $product['kort'],
    'image'       => siteUrl('images/' . $product['foto']),
    'brand'       => ['@type' => 'Brand', 'name' => 'Kamado Breda'],
    'offers'      => [
        '@type'         => 'Offer',
        'price'         => number_format($product['prijs'], 2, '.', ''),
        'priceCurrency' => 'EUR',
        'availability'  => 'https://schema.org/InStock',
        'url'           => siteUrl('kamados/' . $productId),
    ],
];
$extraHead = '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '</script>';

require __DIR__ . '/../includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <nav class="breadcrumb" aria-label="Kruimelpad">
            <a href="./">Home</a> /
            <a href="kamados">Kamado's</a> /
            <span aria-current="page"><?= e($product['naam']) ?></span>
        </nav>

        <div class="product-layout">
            <div>
                <?php include __DIR__ . '/../includes/gallery.php'; ?>
            </div>

            <div class="product-info">
                <span class="badge badge-inline"><?= e($product['badge']) ?></span>
                <h1><?= e($product['naam']) ?></h1>
                <p class="price price-big"><?= prijs($product['prijs']) ?> <small>incl. btw</small></p>
                <p class="lead"><?= e($product['kort']) ?></p>
                <p><?= e($product['tekst']) ?></p>

                <ul class="check-list">
                    <?php foreach ($product['kenmerken'] as $kenmerk): ?>
                        <li><?= e($kenmerk) ?></li>
                    <?php endforeach; ?>
                </ul>

                <div class="buy-box">
                    <?php $bestelId = $productId; $metAantal = true; include __DIR__ . '/../includes/bestelknop.php'; ?>
                    <ul class="buy-box-usps">
                        <li><span class="dot dot-green" aria-hidden="true"></span> Op voorraad in de winkel</li>
                        <li>Gratis bezorging in regio Breda of ophalen in de winkel</li>
                        <li>Veilig betalen met iDEAL, creditcard en meer</li>
                    </ul>
                </div>

                <p class="note">Liever eerst proberen? <a href="masterclasses">Volg een masterclass</a> of mail ons via <a href="mailto:info@kamadobreda.nl?subject=<?= rawurlencode('Vraag over ' . $product['naam']) ?>">info@kamadobreda.nl</a>.</p>
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
                foreach ($kamados as $id => $kamado) {
                    if ($id === $productId) {
                        continue; // het product dat je nu bekijkt niet nog een keer tonen
                    }
                    include __DIR__ . '/../includes/kamado-kaart.php';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
