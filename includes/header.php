<?php
defined('KAMADO_BREDA') || exit;

// Standaardwaarden als een pagina ze niet zelf instelt
$paginaTitel = $paginaTitel ?? 'Kamado Breda';
$actief = $actief ?? '';
$beschrijving = $beschrijving ?? 'Kamado Breda – speciaalzaak in blauwe kamado-barbecues, gietijzeren accessoires en BBQ-masterclasses in Breda. Bestel online of kom langs in de winkel.';
$ogFoto = $ogFoto ?? 'winkel-kamado-breda.jpg';

// Menu-items: route => tekst
$menu = [
    ''              => 'Home',
    'kamados'       => "Kamado's",
    'masterclasses' => 'Masterclasses',
];

$aantalInWagen = aantalInWinkelwagen();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <base href="<?= e(BASIS) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($paginaTitel) ?> | Kamado Breda</title>
    <meta name="description" content="<?= e($beschrijving) ?>">
    <?php if (!empty($geenIndex)): ?>
        <meta name="robots" content="noindex">
    <?php else: ?>
        <link rel="canonical" href="<?= e(siteUrl($route ?? '')) ?>">
    <?php endif; ?>
    <meta name="theme-color" content="#16191f">

    <!-- Voorbeeld bij delen op social media / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Kamado Breda">
    <meta property="og:title" content="<?= e($paginaTitel) ?> | Kamado Breda">
    <meta property="og:description" content="<?= e($beschrijving) ?>">
    <meta property="og:image" content="<?= e(siteUrl('images/' . $ogFoto)) ?>">
    <meta property="og:locale" content="nl_NL">

    <link rel="icon" type="image/png" href="images/favicon.png">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Lettertypes staan op onze eigen server: sneller en geen gegevens naar Google -->
    <link rel="preload" href="fonts/oswald-latin.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="fonts/source-sans-3-latin.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
    <?php if (!empty($preloadFoto)): ?>
        <link rel="preload" as="image" href="images/<?= e($preloadFoto) ?>" type="image/webp" fetchpriority="high">
    <?php endif; ?>

    <script>document.documentElement.classList.add('js');</script>
    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
    <?php
    // Extra scripts per pagina, bijv. $scripts = ['gallery.js'];
    foreach ($scripts ?? [] as $script): ?>
        <script src="<?= e(asset('js/' . $script)) ?>" defer></script>
    <?php endforeach; ?>
    <?= $extraHead ?? '' ?>
</head>
<body>

<a href="<?= e(siteUrl($route ?? '')) ?>#inhoud" class="skip-link">Direct naar de inhoud</a>

<header class="site-header">
    <div class="container header-inner">
        <a href="./" class="logo" aria-label="Kamado Breda – naar de homepage">
            <picture>
                <source srcset="images/logo-sm.webp" type="image/webp">
                <img src="images/logo-sm.png" alt="" width="56" height="56">
            </picture>
            <span>Kamado <strong>Breda</strong></span>
        </a>

        <nav id="hoofdmenu" class="main-nav" aria-label="Hoofdmenu">
            <ul>
                <?php foreach ($menu as $link => $tekst): ?>
                    <li>
                        <a href="<?= e(url($link)) ?>" <?= $actief === $link ? 'class="active" aria-current="page"' : '' ?>>
                            <?= e($tekst) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="masterclasses#inschrijven" class="btn btn-primary btn-small">Inschrijven</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <a href="winkelwagen" class="cart-link <?= $actief === 'winkelwagen' ? 'active' : '' ?>"
               aria-label="Winkelwagen (<?= $aantalInWagen ?> artikelen)" data-cart-link>
                <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span class="cart-count" data-cart-count <?= $aantalInWagen === 0 ? 'hidden' : '' ?>><?= $aantalInWagen ?></span>
            </a>

            <button class="menu-toggle" aria-expanded="false" aria-controls="hoofdmenu" aria-label="Menu openen">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

<main id="inhoud">
<?php
// Melding na een actie (bijv. "toegevoegd aan winkelwagen")
$melding = haalMelding();
if ($melding): ?>
    <div class="container">
        <div class="alert alert-<?= e($melding['soort']) ?> alert-flash" role="status"><?= e($melding['tekst']) ?></div>
    </div>
<?php endif; ?>
